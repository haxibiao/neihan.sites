<?php

namespace App\Traits;

/**
 * 添加到你的Site模型上
 * @package Haxibiao\Content\Traits
 */
trait CanSticky
{

    public function stickyArticles(){
        return $this->stickable(\App\SiteArticle::class);
    }

    public function stickyMovies(){
        return $this->stickable(\App\SiteMovie::class);
    }

    public function stickyPosts(){
        return $this->stickable(\App\SitePost::class);
    }

    public function stickyCategories(){
        return $this->stickable(\App\SiteCategory::class);
    }

    public function stickables()
    {
        return $this->hasMany(\App\Stickable::class);
    }

    public function stickable($related)
    {
        return $this->morphedByMany($related, 'item','stickables')
            ->withPivot(['name', 'page', 'area'])
            ->withTimestamps();
    }

    public function scopeBySiteIds($query, $siteIds)
    {
        return $query->whereIn('id', $siteIds);
    }
}
