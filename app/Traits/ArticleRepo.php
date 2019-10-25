<?php

namespace App\Traits;

use App\Action;
use App\Category;
use App\Image;
use App\Jobs\ProcessVod;
use App\Tip;
use App\Video;
use App\Visit;
use Illuminate\Support\Facades\DB;

trait ArticleRepo
{
    public function savevideoFile($file)
    {
        if ($file) {
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);

            $video->title = $file->getClientOriginalName();
            $video->save();
        }
        $uploadSuccess = $video->saveFile($file);
        if (!$uploadSuccess) {
            //视频上传失败
            abort(500, '视频上传失败');
        }
        return $video->id;
    }

    public function get_title()
    {
        if (!empty($this->title)) {
            return $this->title;
        }

        return str_limit($this->body);
    }

    public function get_description()
    {
        $description = $this->description;
        if (empty($description) || strlen($description) < 2) {
            $body        = html_entity_decode($this->body);
            $description = str_limit(strip_tags($body), 130);
        }
        return str_limit($description, 130);
    }

    public function topImage()
    {
        return $this->image_top;
        //if image not exist locally, go check pord ...
        // $image_top_path = parse_url($this->image_top, PHP_URL_PATH);
        // if (!file_exists(public_path($image_top_path))) {
        //     return env('APP_URL') . $image_top_path;
        // }

        // return url($image_top_path);
    }
    public function primaryImage()
    {
        //全URL,直接顯示
        // if (starts_with($this->image_url, 'http')) {
        //     return $this->image_url;
        // }

        // $image_path = parse_url($this->image_url, PHP_URL_PATH);

        // //如果不是小圖地址,就找小圖的url
        // if (str_contains($image_path, '.small.')) {
        //     $image_path = str_replace('.small', '', $image_path);
        // }
        // $image = Image::firstOrNew([
        //     'path' => $image_path,
        // ]);
        //all place including APP,  需要返回全Uri
        $path = $this->image_url;
        if ($this->type == 'video' || str_contains($path, ['.small.', 'haxibiao'])) {
            return trim_https($this->cover);
        }
        $extension    = pathinfo($path, PATHINFO_EXTENSION);
        $folder       = pathinfo($path, PATHINFO_DIRNAME);
        $url_formater = $folder . '/' . basename($path, '.' . $extension) . '%s' . $extension;
        $image_url    = sprintf($url_formater, '.small.');
        return trim_https(ssl_url($image_url));
    }

    public function hasImage()
    {
        return !empty($this->image_url);
    }

    public function fillForJs()
    {
        $this->user->fillForJs();
        if ($this->category) {
            $this->category->fillForJs();
        }
        $this->title         = $this->title ?: $this->get_description();
        $this->has_image     = $this->hasImage();
        $this->primary_image = $this->primaryImage();
        $this->image_url     = $this->primaryImage();
        $this->description   = $this->get_description();
        $this->url           = $this->url;
        if ($this->video) {
            $this->duration = gmdate('i:s', $this->video->duration);
        }
    }

    public function fillForApp()
    {
        $data              = (object) [];
        $data->id          = $this->id;
        $data->title       = $this->title;
        $data->time_ago    = $this->timeAgo();
        $data->has_image   = $this->hasImage();
        $data->pic         = $this->primaryImage();
        $data->image_url   = $this->primaryImage();
        $data->description = $this->get_description();

        if ($this->user) {
            $user              = $this->user;
            $userForJs         = (object) [];
            $userForJs->avatar = $user->avatarUrl;
            $userForJs->name   = $user->name;
            $userForJs->id     = $user->id;
            $userForJs->time   = $user->updatedAt();
            $data->user        = $userForJs;
        }

        if ($this->category) {
            $category            = $this->category;
            $categoryForJs       = (object) [];
            $categoryForJs->id   = $category->id;
            $categoryForJs->name = $category->name;
            $categoryForJs->logo = $category->iconUrl;
            $data->category      = $categoryForJs;
        }

        //for app api
        $meta       = [];
        $meta[]     = '阅读' . $this->hits;
        $meta[]     = '评论' . $this->count_replies;
        $meta[]     = '喜欢' . $this->count_likes;
        $meta[]     = '赞赏' . $this->count_tips;
        $data->meta = $meta;
        return $data;
    }

    public function link()
    {
        //普通文章
        if ($this->type == 'article') {
            return $this->resoureTypeCN() . '<a href=' . $this->url . '>《' . $this->title . '》</a>';
        }
        //动态
        $title = str_limit($this->body, $limit = 50, $end = '...');
        if (empty($title)) {
            return '<a href=' . $this->url . '>' . $this->resoureTypeCN() . '</a>';
        }
        return $this->resoureTypeCN() . '<a href=' . $this->url . '>《' . $title . '》</a>';
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
                        if ($image->path == $this->image_url) {
                            $has_primary_top = true;
                        }
                        $this->image_top = $image->path_top;
                    }
                }
            }
        }
        //fix primary image url if not
        if (empty($this->image_url)) {
            $this->image_url = $last_img_small_path;
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
        return \DB::table('favorites')
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
        $body = $input['body'];

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

            ProcessVod::dispatch($video);
        }
        //带图
        if (!empty($input['image_urls']) && is_array($input['image_urls'])) {
            //由于传图片的API只返回上传完成后的图片路径,如果改动会对其他地方造成影响。
            //此处将图片路径转换成图片ID
            $image_ids = array_map(function ($url) {
                return intval(pathinfo($url)['filename']);
            }, $input['image_urls']);
            $this->image_url = $input['image_urls'][0];
            $this->has_pic   = 1; //1代表内容含图
            $this->images()->sync($image_ids);
            $this->save();
        }
        app_track_post();
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
}
