<?php

namespace App\Traits;

use App\Collection;
use Auth;

trait UserRelation
{

    //该用户参加的赛季
    public function compare()
    {

    }
    public function followings()
    {
        return $this->hasMany(\App\Follow::class);
    }

    //取出该用户关注的专题
    public function followingCategories()
    {
        return $this->hasMany(\App\Follow::class)->where('followed_type', 'categories');
    }

    public function isSelf()
    {
        return Auth::check() && Auth::id() == $this->id;
    }

    public function transactions()
    {
        return $this->hasMany(\App\Transaction::class);
    }

    public function articles()
    {
        return $this->hasMany(\App\Article::class);
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function messages()
    {
        return $this->hasMany(\App\Message::class);
    }

    public function categories()
    {
        return $this->hasMany(\App\Category::class)->where('type','article');
    }

    public function collections()
    {
        //默认给个文集...
        if (!Collection::where('user_id', $this->id)->count()) {
            $collection = Collection::firstOrNew([
                'user_id' => $this->id,
                'name'    => '日记本',
            ]);
            $collection->save();
            $this->count_collections = 1;
        }

        return $this->hasMany(\App\Collection::class);
    }

    public function actions()
    {
        return $this->hasMany(\App\Action::class);
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    public function collection()
    {
        return $this->hasMany(\App\Collection::class);
    }

    public function isFollow($type, $id)
    {
        return $this->followings()->where('followed_type', get_polymorph_types($type))->where('followed_id', $id)->count() ? true : false;
    }

    public function chats()
    {
        return $this->belongsToMany(\App\Chat::class)->withPivot('with_users', 'unreads');
    }

    public function adminCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('is_admin', 1);
    }

    public function requestCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 0);
    }

    public function joinCategories()
    {
        return $this->belongsToMany(\App\Category::class)->wherePivot('approved', 1);
    }

    public function introduction()
    {
        return !empty($this->introduction) ? $this->introduction : '这个人比较神秘,什么都没有留下...';
    }

}
