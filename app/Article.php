<?php

namespace App;

use App\Action;
use App\Model;
use App\Tip;
use Auth;
use App\Traits\Playable;
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
        'video_id'
    ];

    protected $touches = ['category', 'collections', 'categories'];

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

    public function videos()
    {
        return $this->belongsToMany('App\Video')->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany('App\Collection')->withTimestamps();
    }

    public function tips()
    {
        return $this->morphMany(\App\Tip::class, 'tipable');
    }

    //computed props or methods -------------------------

    public function description()
    {
        $description = html_entity_decode($this->description);
        $body        = html_entity_decode($this->body);
        $description = empty($description) ? str_limit(strip_tags($body), 80) : str_limit($description, 80);
        return $description;
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
        if( $this->type == 'video' ){ 
            return env('APP_URL') . $this->image_url;
        }
        //爬蟲文章的圖片,直接顯示
        if (str_contains($this->image_url, 'haxibiao.com/storage/image')) {
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
        $model_is_video = $this->type == 'video';
        if( $model_is_video ){
            return !empty($this->image_url); 
        }
        $image_url = parse_url($this->image_url, PHP_URL_PATH);
        $image_url = str_replace('.small', '', $image_url);
        $image     = Image::firstOrNew([
            'path' => $image_url,
        ]);
        return $image->id;
    }

    public function fillForJs()
    {
        $this->user->fillForJs();
        if ($this->category) {
            $this->category->fillForJs();
        }
        $this->has_image     = $this->hasImage();
        $this->primary_image = $this->primaryImage();
        $this->image_url     = $this->primaryImage();
        $this->description   = $this->description();
        $this->url           = $this->content_url();
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
        $data->description = $this->description();

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
        return '<a href="/article/' . $this->id . '">' . $this->title . '</a>';
    }

    public function recordAction()
    {
        if ($this->status > 0) {
            $action = Action::firstOrNew([
                'user_id'         => Auth::id(),
                'actionable_type' => 'articles',
                'actionable_id'   => $this->id,
            ]);
            $action->save();
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

                //auto get is_top an image_top
                if ($image->path_top) {
                    // $this->is_top    = 1;
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
        $tip->amount  = $tip->amount + $amount;
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
    public function recordBrowserHistory(){
        //增加点击量  
        $this->hits = $this->hits + 1;
        $agent         = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile()) {
            $this->hits_mobile = $this->hits_mobile + 1;
        }
        if ($agent->isPhone()) {
            $this->hits_phone = $this->hits_phone + 1;
        }
        if ($agent->match('micromessenger')) {
            $this->hits_wechat = $this->hits_wechat + 1;
        }
        if ($agent->isRobot()) {
            $this->hits_robot = $this->hits_robot + 1;
        }
        //记录浏览历史
        if (checkUser()) { 
            $user = getUser();
            //如果重复浏览只更新纪录的时间戳            
            $visit = \App\Visit::firstOrNew([
                'user_id'      => $user->id,
                'visited_type' => $this->type,
                'visited_id'   => $this->id,
            ]);
            $visit->save(); 
        }
        $this->save();
    }
    /**
     * @Desc      返回资源的url
     * 
     * @Author   czg
     * @DateTime 2018-06-29
     * @return   [type]     [description]
     */
    public function content_url(){
        $url_template = '/%s/%d';
        if( $this->type == 'video' ){
            return sprintf($url_template, $this->type, $this->video_id);
        }
        return sprintf($url_template, $this->type, $this->id);
    }
}