<?php


namespace App\Traits;


trait CanLike
{
    public function likes()
    {
        return $this->hasMany(\App\Like::class);
    }

    // 兼容旧接口用
    public function likedArticles()
    {
        return $this->likes()
            ->byLikableType('articles');
    }

    // 兼容旧接口用
    public function likedSolutions()
    {
        return $this->likes()
            ->byLikableType('answers');
    }

    // 兼容旧接口用
    public function likedTableIds($likavleType, $likableIds)
    {
        return $this->likes()->select('likable_id')
            ->whereIn('likable_id', $likableIds)
            ->where('likable_type', $likavleType)
            ->get()
            ->pluck('likable_id');
    }

    // 兼容旧接口用
    public function isLiked($type, $id)
    {
        return $this->likes()->where('likable_type', get_polymorph_types($type))->where('likable_id', $id)->count() ? true : false;
    }

    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }
}