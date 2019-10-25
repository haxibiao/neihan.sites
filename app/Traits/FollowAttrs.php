<?php

namespace App\Traits;

trait FollowAttrs
{
    public function getNameAttribute()
    {
        return $this->followed->name;
    }

    public function getlatestArticleTitleAttribute()
    {
        $latest_article = $this->followed->publishedArticles()->latest()->first();
        if (empty($latest_article)) {
            return null;
        }
        return $latest_article->title;
    }

    public function getdynamicMsgAttribute()
    {
        $dynamicCount = $this->followed->publishedArticles()->where('articles.created_at', '>', $this->updated_at)->count();
        if ($dynamicCount == 0) {
            //没有动态
            return null;
        } else if ($dynamicCount > 99) {
            //超过99条
            return '99+篇文章';
        } else {
            //超过99条动态信息
            return $dynamicCount . '篇文章';
        }
    }
}
