<?php

namespace App;

use App\Traits\CanBeLiked;
use Haxibiao\Content\Article as BaseArticle;

class Article extends BaseArticle
{
    use CanBeLiked;

    public static function boot()
    {
        parent::boot();

        //保存时触发
        self::saving(function ($article) {
            $description          = $article->description;
            $body              = $article->body;
            $article->description = app('SensitiveUtils')->replace($description, '*');
            $article->body        = app('SensitiveUtils')->replace($body, '*');
        });
    }

}
