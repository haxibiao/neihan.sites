<?php


namespace App\Traits;


use App\Tag;

trait CanTag
{
    public function tags()
    {
       return $this->hasMany(\App\Tag::class);
    }

    public function resovleUserTags($root, array $args, $context){
        return $root->tags();
    }
}