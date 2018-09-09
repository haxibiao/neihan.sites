<?php

namespace App;

use App\Action;
use App\Category;
use App\Model;
use App\Tip;
use App\Traits\Playable;
use App\Video;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    use Playable;

    protected $fillable = [
        'title',
        'keywords',
        'description',
        'author',
        'author_id',
        'user_name',
        'user_id',
        'category_id',
        'collection_id',
        'body',
        'count_words',
        'image_url',
        'is_top',
        'status',
        'source_url',
        'hits',
        'count_likes',
        'count_comments',
        'type',
        'video_url',
        'video_id',
        'slug',
    ];

    protected $touches = ['category', 'collection', 'categories'];

    protected $dates = ['edited_at', 'delay_time', 'commented'];

    //relations
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //主专题
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    //主文集
    public function collection()
    {
        return $this->belongsTo('App\Collection');
    }

    //所有分类关系，包含未审核通过的
    public function allCategories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    //有效的分类关系
    public function categories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->wherePivot('submit', '已收录')
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }
    public function fixcategories()
    {
        return $this->belongsToMany(\App\Category::class)
            ->wherePivot('submit', null)
            ->withPivot(['id', 'submit'])
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function tags1()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function images()
    {
        return $this->belongsToMany('App\Image')->withTimestamps();
    }

    public function tips()
    {
        return $this->morphMany(\App\Tip::class, 'tipable');
    }

    public function relatedVideoPostsQuery()
    {
        return Article::with(['video' => function ($query) {
            //过滤软删除的video
            $query->whereStatus(1);
        }])->where('type', 'video')
            ->whereIn('category_id', $this->categories->pluck('id'));
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- service ----------------------------- */
    /* --------------------------------------------------------------------- */

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
        //if image not exist locally, go check pord ...
        $image_top_path = parse_url($this->image_top, PHP_URL_PATH);
        if (!file_exists(public_path($image_top_path))) {
            return env('APP_URL') . $image_top_path;
        }

        return url($image_top_path);
    }
    public function primaryImage()
    {
        //全URL,直接顯示
        if (starts_with($this->image_url, 'http')) {
            return $this->image_url;
        }

        $image_path = parse_url($this->image_url, PHP_URL_PATH);

        //如果不是小圖地址,就找小圖的url
        if (str_contains($image_path, '.small.')) {
            $image_path = str_replace('.small', '', $image_path);
        }
        $image = Image::firstOrNew([
            'path' => $image_path,
        ]);
        //all place including APP,  需要返回全Uri
        return $image->url_small();
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
        $this->url           = $this->content_url();
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
            $userForJs->avatar = $user->avatar();
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
            $categoryForJs->logo = $category->smallLogo();
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
        if( $this->type == 'article' ){
            return $this->resoureTypeCN().'<a href=' . $this->content_url() . '>《' .$this->title. '》</a>';
        }
        //动态
        $title =  str_limit($this->body, $limit = 50, $end = '...');
        if(empty($title)){
            return '<a href=' . $this->content_url() . '>'. $this->resoureTypeCN() .'</a>';
        }
        return $this->resoureTypeCN() . '<a href=' . $this->content_url() . '>《' .$title. '》</a>';
    }

    public function recordAction()
    {
        if ($this->status > 0) {
            $action = Action::updateOrCreate([
                'user_id'         => Auth::id(),
                'actionable_type' => 'articles',
                'actionable_id'   => $this->id,
            ]);
        }
    }

    public function parsedBody()
    {
        $this->body = parse_image($this->body);
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
        $pattern_img_path = '/src=\"([^\"]*?)(\/storage\/image\/\d+\.(jpg|gif|png|jpeg))\"/';
        if (preg_match_all($pattern_img_path, $this->body, $match)) {
            $images = $match[2];
        }
        $imgs             = [];
        $pattern_img_path = '/src=\"([^\"]*?)(\/storage\/img\/\d+\.(jpg|gif|png|jpeg))\"/';
        if (preg_match_all($pattern_img_path, $this->body, $match)) {
            $imgs = $match[2];
        }
        $imgs                = array_merge($imgs, $images);
        $image_ids           = [];
        $has_primary_top     = false;
        $last_img_small_path = '';
        foreach ($imgs as $img) {
            $path      = parse_url($img)['path'];
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $path      = str_replace('.small.' . $extension, '', $path);
            if (str_contains($img, 'base64') || str_contains($path, 'storage/video')) {
                continue;
            }
            $image = Image::firstOrNew([
                'path' => $path,
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

        $tip          = \App\Tip::firstOrNew($data);
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
        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile()) {
            $this->hits_mobile = $this->hits_mobile + 1;
        }
        if ($agent->isPhone()) {
            $this->hits_phone = $this->hits_phone + 1;
        }
        if ($agent->match('micromessenger')) {
            $this->hits_wechat = $this->hits_wechat + 1;
        }
        //增加点击量
        if ($agent->isRobot()) {
            $this->hits_robot = $this->hits_robot + 1;
        } else {
            //非爬虫请求才统计热度
            $this->hits = $this->hits + 1;
            //记录浏览历史
            if (checkUser()) {
                $user = getUser();
                //如果重复浏览只更新纪录的时间戳
                $visit = \App\Visit::firstOrNew([
                    'user_id'      => $user->id,
                    'visited_type' => str_plural($this->type),
                    'visited_id'   => $this->id,
                ]);
                $visit->save();
            }
        }
        $this->timestamps = false;
        $this->save();
        $this->timestamps = true;
    }
    /**
     * @Desc      返回资源的url
     * @DateTime 2018-06-29
     * @return   [type]     [description]
     */
    public function content_url()
    {
        $url_template = '/%s/%d';
        if ($this->type == 'video') {
            return sprintf($url_template, $this->type, $this->video_id);
        }
        if ($this->type == 'post') {
            return sprintf($url_template, 'article', $this->id);
        }
        return sprintf($url_template, $this->type, $this->id);
    }
    /**
     * @Desc     创建动态
     * @DateTime 2018-07-23
     * @param    [type]     $input [description]
     * @return   [type]            [description]
     */
    public function createPost($input)
    {
        $user  = getUser();
        $body  = $input['body'];
        $title = isset($input['title']) ? $input['title'] : str_limit($body, $limit = 20, $end = '...');

        // $this->title = $title; //暂时不保存多余的title给post
        $this->body        = $body;
        $this->description = str_limit($body, 280); //截取微博那么长的内容存简介
        $this->status      = 1; //直接发布
        $this->type        = 'post';
        $this->user_id     = $user->id;
        $this->save();
        //带视频
        if (isset($input['video_id'])) {
            $this->status = 0; //视频的话，等视频截图转码完成，自动会发布的
            $this->type   = 'video';
            $video        = Video::findOrFail($input['video_id']);
            $video->title = $title;
            $video->save();
            $this->video_id = $video->id; //关联上视频
            $this->save();

            //20秒后自动检查视频vod上的结果(截图取的是前9秒的九张图，应该在10秒内成功，这步操作是因为crontab好几个服务器不稳定)
            \App\Jobs\SyncVodResult::dispatch($video)->delay(now()->addSeconds(20)); 
        }
        //带图
        if (isset($input['image_urls']) && is_array($input['image_urls']) && !empty($input['image_urls'])) {
            //由于传图片的API只返回上传完成后的图片路径,如果改动会对其他地方造成影响。
            //此处将图片路径转换成图片ID
            $image_ids = array_map(function ($url) {
                return intval(pathinfo($url)['filename']);
            }, $input['image_urls']);
            $this->image_url = $input['image_urls'][0];
            $this->has_pic   = 1; //1代表内容含图
            $this->save();
            $this->images()->sync($image_ids);
        }
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

    public function save(array $options = array())
    {
        $this->description = $this->get_description();
        parent::save($options);
    }
}
