<?php

namespace App\Traits;

use App\Action;
use App\Article;
use App\Category;
use App\Exceptions\GQLException;
use App\Gold;
use App\Helpers\BadWord\BadWordUtils;
use App\Image;
use App\Notifications\ReceiveAward;
use App\Tag;
use App\Tip;
use App\Video;
use App\Visit;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ArticleRepo
{
    /**
     * @param UploadedFile $file
     * @return int|mixed
     * @throws \Throwable
     */
    public function saveVideoFile(UploadedFile $file)
    {
        $hash  = md5_file($file->getRealPath());
        $video = \App\Video::firstOrNew([
            'hash' => $hash,
        ]);
//        秒传
        if (isset($video->id)) {
            return $video->id;
        }

        $uploadSuccess = $video->saveFile($file);
        throw_if(!$uploadSuccess, Exception::class, '视频上传失败，请联系管理员小哥');
        return $video->id;
    }

    public function fillForJs()
    {
        if ($this->user) {
            $this->user->fillForJs();
        }

        if ($this->category) {
            $this->category->fillForJs();
        }

        $this->description = $this->summary;

        if ($this->video) {
            $this->duration     = gmdate('i:s', $this->video->duration);
            $this->cover        = $this->video->cover_url;
            $this->video->cover = $this->video->cover_url;
        }
    }

    public function recordAction()
    {
        if ($this->status > 0) {
            $action = Action::updateOrCreate([
                'user_id'         => getUser()->id,
                'actionable_type' => 'articles',
                'actionable_id'   => $this->id,
                'status'          => 1,
            ]);
        }
    }

    public function parsedBody($environment = null)
    {
        $this->body = parse_image($this->body, $environment);
        $pattern    = "/<img alt=\"(.*?)\" ([^>]*?)>/is";
        preg_match_all($pattern, $this->body, $match);

        //try replace first image alt ...
        // if ($match && count($match)) {
        //     $image_first = str_replace($match[1][0], $this->title, $match[0][0]);
        //     $this->body  = str_replace($match[0][0], $image_first, $this->body);
        // }

        $this->body = parse_video($this->body);
        return $this->body;
    }

    public function saveRelatedImagesFromBody()
    {
        $images           = [];
        $pattern_img_path = '/src=\"([^\"]*?(\/storage\/image\/\d+\.(jpg|gif|png|jpeg)))\"/';
        if (preg_match_all($pattern_img_path, $this->body, $match)) {
            $images = $match[1]; //考虑目前图片全部在Cos上,存Cos全路径.
        }
        $imgs             = [];
        $pattern_img_path = '/src=\"([^\"]*?(\/storage\/img\/\d+\.(jpg|gif|png|jpeg)))\"/';
        if (preg_match_all($pattern_img_path, $this->body, $match)) {
            $imgs = $match[1];
        }
        $imgs                = array_merge($imgs, $images);
        $image_ids           = [];
        $has_primary_top     = false;
        $last_img_small_path = '';
        foreach ($imgs as $img) {
            // $path      = parse_url($img)['path'];
            $path      = $img;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $path      = str_replace('.small.' . $extension, '', $path);
            if (str_contains($img, 'base64') || str_contains($path, 'storage/video')) {
                continue;
            }
            $image = Image::firstOrNew([
                'path' => $path,
            ]);
            $image = $image->id ? $image : Image::firstOrNew([
                'path' => str_replace('/image/', '/img/', $path),
            ]);
            if ($image->id) {
                $image_ids[]  = $image->id;
                $image->count = $image->count + 1;
                $image->title = $this->title;
                $image->save();
                $last_img_small_path = $image->path_small();
                //自动上轮播图，只要图片满足条件
                if ($image->path_top) {
                    $this->is_top = 1;
                    if (!$has_primary_top) {
                        if ($image->path == $this->cover) {
                            $has_primary_top = true;
                        }
                        $this->image_top = $image->path_top;
                    }
                }
            }
        }

        //沒有可以上首頁的圖片，上首頁失敗
        if (!$has_primary_top) {
            $this->is_top = 0;
        }
        $this->save();
        //如果文章图片关系中得图片地址不在文中，清除掉！
        $this->images()->sync($image_ids);
    }

    public function report($type, $reason)
    {
        $this->count_reports = $this->count_reports + 1;

        $json = json_decode($this->json);
        if (!$json) {
            $json = (object) [];
        }

        $user    = getUser();
        $reports = [];
        if (isset($json->reports)) {
            $reports = $json->reports;
        }

        $report_data = [
            'type'   => $type,
            'reason' => $reason,
        ];
        $reports[] = [
            $user->id => $report_data,
        ];

        $json->reports = $reports;
        $this->json    = json_encode($json, JSON_UNESCAPED_UNICODE);
        $this->save();
    }

    public function reports()
    {
        $json = json_decode($this->json, true);
        if (empty($json)) {
            $json = [];
        }
        $reports = [];
        if (isset($json['reports'])) {
            $reports = $json['reports'];
        }
        return $reports;
    }
    /**
     * @Desc     该文章是否被当前登录的用户收藏，如果用户没有登录将返回false
     *
     * @Author   czg
     * @DateTime 2018-06-12
     * @return   bool
     */
    public function currentUserHasFavorited()
    {
        //未登录状态
        if (!checkUser()) {
            return false;
        }
        $loginUser = getUser();
        return DB::table('favorites')
            ->where('user_id', $loginUser->id)
            ->where('faved_id', $this->id)
            ->where('faved_type', 'articles')
            ->exists();
    }

    public function tip($amount, $message = '')
    {
        $user = getUser();

        //保存赞赏记录
        $data = [
            'user_id'      => $user->id,
            'tipable_id'   => $this->id,
            'tipable_type' => 'articles',
        ];

        $tip = \App\Tip::firstOrNew($data);
        //$tip->amount  = $tip->amount + $amount;
        $tip->amount  = $amount;
        $tip->message = $message; //tips:: 当上多次，总计了总量，留言只保留最后一句，之前的应该通过通知发给用户了
        $tip->save();

        //action
        $action = \App\Action::create([
            'user_id'         => $user->id,
            'actionable_type' => 'tips',
            'actionable_id'   => $tip->id,
        ]);

        //更新文章赞赏数
        $this->count_tips = $this->tips()->count();
        $this->save();

        //赞赏消息提醒
        $this->user->notify(new \App\Notifications\ArticleTiped($this, $user, $tip));

        return $tip;
    }
    /**
     * @Desc     记录用户浏览记录
     * @Author   czg
     * @DateTime 2018-06-28
     * @return   [type]     [description]
     */
    public function recordBrowserHistory()
    {
        if (isMobile()) {
            $this->hits_mobile = $this->hits_mobile + 1;
        }
        if (isPhone()) {
            $this->hits_phone = $this->hits_phone + 1;
        }
        if (match('micromessenger')) {
            $this->hits_wechat = $this->hits_wechat + 1;
        }
        //增加点击量
        if (isRobot()) {
            $this->hits_robot = $this->hits_robot + 1;
        } else {
            //非爬虫请求才统计热度
            $this->hits = $this->hits + 1;
            //记录浏览历史
            if (checkUser()) {
                $user = getUser();
                //如果重复浏览只更新纪录的时间戳
                $visit = Visit::firstOrNew([
                    'user_id'      => $user->id,
                    'visited_type' => str_plural($this->type),
                    'visited_id'   => $this->type == 'video' ? $this->video_id : $this->id,
                ]);
                $visit->save();
            }
        }
        $this->timestamps = false;
        $this->save();
        $this->timestamps = true;
    }

    /**
     * @Author      XXM
     * @DateTime    2018-10-27
     * @description [上传外部链接的图片到Cos]
     * @return      [type]        [description]
     */
    public function saveExternalImage()
    {
        //线上环境 使用
        if (!is_prod()) {
            return null;
        }
        $images     = [];
        $image_tags = [];
        //匹配出所有Image
        if (preg_match_all('/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/', $this->body, $match)) {
            $image_tags = $match[0];
            $images     = $match[1];
        }
        //过滤掉cdn链接
        $images = array_filter($images, function ($url) {
            if (!str_contains($url, env('APP_DOMAIN'))) {
                return $url;
            }
        });
        $image_tags = array_filter($image_tags, function ($url) {
            if (!str_contains($url, env('APP_DOMAIN'))) {
                return $url;
            }
        });

        //保存外部链接图片
        if ($images) {
            foreach ($images as $index => $image) {
                //匹配URL格式是否正常
                $regx = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
                if (preg_match($regx, $image)) {
                    $image_model          = new Image();
                    $image_model->user_id = getUser()->id;
                    $image_model->save();
                    $path = $image_model->save_image($image, $this->title);

                    //替换正文Image 标签 保守办法 只替换Image
                    $new_image_tag = str_replace($image, $path, $image_tags[$index]);
                    $this->body    = str_replace($image_tags[$index], $new_image_tag, $this->body);
                    $this->save();
                }
            }
        }
    }

    /**
     * @Author      XXM
     * @DateTime    2018-11-11
     * @description [改变相关的动态 后期将这块放进队列中处理]
     * @return      [null]
     */
    public function changeAction()
    {
        //改变 发表文章的动态
        $action = $this->morphMany(\App\Action::class, 'actionable')->first();
        if ($action) {
            $action->status = $this->status;
            $action->save(['timestamps' => false]);
        }

        //改变评论 动态
        $comments = $this->comments;
        foreach ($comments as $comment) {
            $comment_action = $comment->morphMany(\App\Action::class, 'actionable')->first();
            if ($comment_action) {
                $comment_action->status = $this->status;
                $comment_action->save(['timestamps' => false]);
            }
            //改变被喜欢的评论 动态
            foreach ($comment->hasManyLikes as $comment_like) {
                $comment_like_action = $comment_like->morphMany(\App\Action::class, 'actionable')->first();
                if ($comment_like_action) {
                    $comment_like_action->status = $this->status;
                    $comment_like_action->save(['timestamps' => false]);
                }
            }
        }

        //改变喜欢 动态
        $likes = $this->likes;
        foreach ($likes as $like) {
            $like_action = $like->morphMany(\App\Action::class, 'actionable')->first();
            if ($like_action) {
                $like_action->status = $this->status;
                $like_action->save(['timestamps' => false]);
            }
        }

        //改变收藏
        $favorites = $this->favorites;
        foreach ($favorites as $favorite) {
            $favorite_action = $favorite->morphMany(\App\Action::class, 'actionable')->first();
            if (favorite_action) {
                $favorite_action->status = $this->status;
                $favorite_action->save(['timestamps' => false]);
            }
        }
    }

    public function createPost($input)
    {

        $user = getUser();
        if ($user->isBlack()) {
            throw new GQLException('发布失败,你以被禁言');
        }

        $body = $input['body'];
        if (BadWordUtils::check($body)) {
            throw new GQLException('发布的内容中含有包含非法内容,请删除后再试!');
        }
        // $this->title = $this->getPostTitle(); //暂时不保存多余的title给post
        $this->body        = $body;
        $this->description = str_limit($body, 280); //截取微博那么长的内容存简介
        $this->status      = 1; //直接发布
        $this->type        = 'post';
        $this->user_id     = $user->id;
        $this->save();
        //带视频
        if (isset($input['video_id'])) {

            $video        = Video::findOrFail($input['video_id']);
            $video->title = $this->getPostTitle(); //同步上标题
            $video->save();

            $this->status   = 0; //视频的话，等视频截图转码完成，自动会发布动态的
            $this->type     = 'video';
            $this->video_id = $video->id; //关联上视频
            $this->save();
        }
        //带图
        if (!empty($input['image_urls']) && is_array($input['image_urls'])) {
            //由于传图片的API只返回上传完成后的图片路径,如果改动会对其他地方造成影响。
            //此处将图片路径转换成图片ID
            $image_ids = array_map(function ($url) {
                return intval(pathinfo($url)['filename']);
            }, $input['image_urls']);
            $this->images()->sync($image_ids);
            $this->save();
        }
        app_track_user('发布动态', 'post');
        return $this;
    }

    //直接收录到专题的操作
    public function saveCategories($categories_json)
    {
        $article          = $this;
        $old_categories   = $article->categories;
        $new_categories   = json_decode($categories_json);
        $new_category_ids = [];
        //记录选得第一个做文章的主分类，投稿的话，记最后一个专题做主专题
        if (!empty($new_categories)) {
            $article->category_id = $new_categories[0]->id;
            $article->save();

            foreach ($new_categories as $cate) {
                $new_category_ids[] = $cate->id;
            }
        }
        //sync
        $params = [];
        foreach ($new_category_ids as $category_id) {
            $params[$category_id] = [
                'submit' => '已收录',
            ];
        }
        $article->categories()->sync($params);
        // $article->categories()->sync($new_category_ids);

        //re-count
        if (is_array($new_categories)) {
            foreach ($new_categories as $category) {
                //更新新分类文章数
                if ($category = Category::find($category->id)) {
                    $category->count        = $category->publishedArticles()->count();
                    $category->count_videos = $category->videoPosts()->count();
                    $category->save();
                }
            }
        }
        foreach ($old_categories as $category) {
            //更新旧分类文章数
            $category->count        = $category->publishedArticles()->count();
            $category->count_videos = $category->videoPosts()->count();
            $category->save();
        }
    }

    /**
     * @Desc     资源类型
     * @DateTime 2018-07-24
     * @return   [type]     [description]
     */
    public function resoureTypeCN()
    {
        $type = $this->type;
        switch ($type) {
            case 'video':
                return '视频';
            case 'post':
                return '动态';
            default:
                break;
        }
        return '文章';
    }

    /**
     * 根据抖音视频信息 转存到 公司的cos
     * FIXME  待 article 与 video 模块重构后，这也需要变化
     * @param array $info
     * @return void
     * @author zengdawei
     */
    public function parseDouyinLink(array $info)
    {

        $status = array_get($info, 'code');
        // 判断 爬取 信息是否成功
        if ($status == 1) {

            // 爬取出来的信息
            $info = $info['0'];
            $url  = $info['play_url'];

            // 填充模型
            $hash           = md5_file($url);
            $video          = new Video();
            $video->title   = $info['aweme_id'];
            $video->hash    = $hash;
            $video->user_id = getUserId();
            $video->save();

            $cosPath = 'video/' . $video->id . '.mp4';

            try {
                //本地存一份用于截图
                \Storage::disk('public')->put($cosPath, file_get_contents($url));
                $video->disk = 'local'; //先标记为成功保存到本地

                $video->path = $cosPath;
                $video->save();

                //同步上传到cos
                $cosDisk = \Storage::cloud();
                $cosDisk->put($cosPath, \Storage::disk('public')->get($cosPath));
                $video->disk = 'cos';
                $video->save();

                // sync 爬取信息
                $this->video_id    = $video->id;
                $this->title       = $info['desc'];
                $this->description = $info['desc'];
                $this->body        = $info['desc'];

                $this->user_id = checkUser()->id;
                $this->type    = 'video';
                $this->save();

                // 防止 gql 属性找不到
                return Article::find($this->id);

            } catch (\Exception $ex) {
                \Log::error("video save exception" . $ex->getMessage());
            }

        }
        throw new Exception('分享失败，请检查您的分享信息是否正确!');
    }

    public function get_description()
    {
        return str_limit($this->description, 10);
    }

    public function get_title()
    {
        return str_limit($this->title, 10);
    }

    public static function makeNewReviewId($prefixNum = null)
    {
        $maxNum    = 100000;
        $prefixNum = is_null($prefixNum) ? today()->format('Ymd') : $prefixNum;
        $reviewId  = intval($prefixNum) * $maxNum + mt_rand(1, $maxNum - 1);
        return $reviewId;
    }

    public function processSpider(array $data)
    {
        //同步爬虫标签
        $this->syncSpiderTags(Arr::get($data, 'raw.item_list.0.desc', ''));
        //同步爬虫视频
        $video = $this->syncSpiderVideo(Arr::get($data, 'video'));
        //创建热门分类
        $category = $this->createHotCategory();

        //发布article
        $this->type     = 'video';
        $this->video_id = data_get($video, 'id');
        $this->setStatus(Article::STATUS_ONLINE);
        $this->category_id = data_get($category, 'id');
        $this->review_id   = Article::makeNewReviewId();
        $this->save();
        $this->categories()->sync([$category->id]);

        //奖励用户
        $user = $this->user;
        if (!is_null($user)) {
            $user->notify(new ReceiveAward('发布视频动态奖励', 10, $user, $this->id));
            Gold::makeIncome($user, 10, '发布视频动态奖励');
        }
    }

    public function syncSpiderTags($description)
    {
        $description = preg_replace('/@([\w]+)/u', '', $description);
        preg_match_all('/#([\w]+)/u', $description, $topicArr);

        if ($topicArr[1]) {
            $tags = [];
            foreach ($topicArr[1] as $topic) {
                if (Str::contains($topic, '抖音')) {
                    continue;
                }
                $tag = Tag::firstOrCreate([
                    'name' => $topic,
                ], [
                    'user_id' => 1,
                ]);
                $tags[] = $tag->id;
            }
            $this->tags()->sync($tags);
        }
    }

    public function syncSpiderVideo($data)
    {
        $hash     = Arr::get($data, 'hash');
        $json     = Arr::get($data, 'json');
        $mediaUrl = Arr::get($data, 'url');
        $coverUrl = Arr::get($data, 'cover');
        if (!empty($hash)) {
            $video = Video::firstOrNew(['hash' => $hash]);
            //同步视频信息
            $video->setJsonData('metaInfo', $json);
            $video->setJsonData('server', $mediaUrl);
            $video->user_id = $this->user_id;

            //更改VOD地址
            $video->disk         = 'vod';
            $video->qcvod_fileid = Arr::get($json, 'vod.FileId');
            $video->path         = $mediaUrl;
            $video->save();

            //保存视频截图 && 同步填充信息
            $video->status   = 1;
            $video->cover    = $coverUrl;
            $video->duration = Arr::get($data, 'duration');
            $video->setJsonData('cover', $coverUrl);
            $video->setJsonData('width', Arr::get($data, 'width'));
            $video->setJsonData('height', Arr::get($data, 'height'));
            $video->save();

            return $video;
        }
    }

    public function createHotCategory()
    {
        $category = Category::firstOrNew([
            'name' => '我要上热门',
        ]);
        if (!$category->id) {
            $category->name_en = 'woyaoshangremeng';
            $category->status  = 1;
            $category->user_id = 1;
            $category->save();
        }

        return $category;
    }

    public function setStatus($status)
    {
        $this->submit = $this->status = $status;
    }

    public function isSpider()
    {
        return Str::contains($this->source_url, 'v.douyin.com');
    }

    public function isReviewing()
    {
        return $this->status == Article::STATUS_REVIEW;
    }

    public function spiderParse($url)
    {
        $hookUrl  = 'hotfix.ainicheng.com/api/media/hook';
        $data     = [];
        $client   = new Client();
        $response = $client->request('GET', 'http://media.haxibiao.com/api/v1/spider/store', [
            'http_errors' => false,
            'query'       => [
                'source_url' => urlencode(trim($url)),
                'hook_url'   => $hookUrl,
            ],
        ]);
        throw_if($response->getStatusCode() == 404, GQLException::class, '您分享的链接不存在,请稍后再试!');
        $contents = $response->getBody()->getContents();
        if (!empty($contents)) {
            $contents = json_decode($contents, true);
            $data     = Arr::get($contents, 'data');
        }

        return $data;
    }

    public function startSpider()
    {
        if ($this->isReviewing() && $this->isSpider()) {
            $data  = $this->spiderParse($this->source_url);
            $video = Arr::get($data, 'video');
            if (is_array($video)) {
                $this->processSpider($data);
            }
            $this->save();
        }
    }
}
