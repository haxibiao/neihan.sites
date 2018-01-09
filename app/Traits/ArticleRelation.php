<?php

namespace App\Traits;

use App\Collection;
use Auth;

trait ArticleRelation{

   public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class)->withPivot('submit')->withTimestamps();
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
}