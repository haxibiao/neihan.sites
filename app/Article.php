<?php

namespace App;

use App\Action;
use App\Model;
use Auth;

class Article extends Model
{
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
        'image_url',
        'is_top',
        'status',
        'source_url',
    ];

    protected $touches = ['category', 'collections', 'categories'];

    protected $dates = ['edited_at', 'delay_time'];

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
        $image_url = parse_url($this->image_url, PHP_URL_PATH);
        $result    = str_replace('.small', '', $image_url) == str_replace('.top', '', $this->image_top);

        //改写了添加的置顶轮播图的取的机制，TODO::上轮播图那个地方以后要修复一下
        if ($result && file_exists(public_path($image_url))) {
            $image_top = parse_url($this->image_top, PHP_URL_PATH);
            return env('APP_URL') . $image_top;
        } else {
            $image_url = str_replace('.small', '.top', $image_url);
        }
        return url($image_url);
        // if (file_exists(public_path($image_url))) {
        // }
        // $image_top = parse_url($this->image_top, PHP_URL_PATH);
        // return env('APP_URL') . $image_top;
    }

    public function primaryImage()
    {
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
}
