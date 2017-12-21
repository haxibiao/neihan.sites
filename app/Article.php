<?php

namespace App;

use App\Model;

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
        'body',
        'image_url',
        'is_top',
        'status',
    ];

    protected $dates = ['edited_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function tags1()
    {
        return $this->belongsToMany(\App\Tag::class);
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function images()
    {
        return $this->belongsToMany('App\Image');
    }

    public function videos()
    {
        return $this->belongsToMany('App\Video');
    }

    public function collections()
    {
        return $this->belongsToMany(\App\Collection::class);
    }

    public function commments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function tips()
    {
        return $this->morphMany(\App\Tip::class, 'tipable');
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    //计算用方法

    public function description()
    {
        return !empty($this->description) ? $this->description : str_limit(strip_tags($this->body), 200);
    }

    public function primaryImage()
    {
        $image_url_path = parse_url($this->image_url, PHP_URL_PATH);
        $image          = Image::firstOrNew([
            'path' => $image_url_path,
        ]);
        if(str_contains($this->image_url, "haxibiao")){
            return $this->image_url;
        }
        return $image->path_small();
    }

    public function hasImage()
    {
        $image_url_path = parse_url($this->image_url, PHP_URL_PATH);
        $image          = Image::firstOrNew([
            'path' => $image_url_path,
        ]);
        return $image->id;
    }

    public function fillForJs()
    {
        $this->time_ago      = $this->timeAgo();
        $this->has_image     = $this->hasImage();
        $this->primary_image = $this->primaryImage();
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->user_id;
    }
}
