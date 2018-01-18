<?php

namespace App;

use App\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'user_id',
        'parent_id',
        'type',
        'order',
    ];

    public function questions()
    {
        return $this->belongsToMany(\App\Question::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function admins()
    {
        return $this->belongsToMany(\App\User::class)->wherePivot('is_admin', 1);
    }

    public function authors()
    {
        return $this->belongsToMany(\App\User::class)->wherePivot('approved', 1);
    }

    public function member()
    {
        return $this->belongsToMany(\App\User::class)->withPivot('approved', 'is_admin');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article')->withPivot('submit')->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(\App\Category::class, 'parent_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class, 'object_id');
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function videos()
    {
        return $this->belongsToMany(\App\Video::class);
    }
    public function favorites()
    {
        return $this->morphMany(\App\Favorite::class, 'faved');
    }

    public function smallLogo()
    {
        return str_replace(".logo.jpg", ".logo.small.jpg", $this->logo);
    }

    //用于计算的方法

    public function topAdmins()
    {
        $topAdmins = $this->admins()->get();
        // ->orderBy('id', 'desc')->take(10)->get();
        foreach ($topAdmins as $admin) {
            $admin->isCreator = $admin->id == $this->user_id;
        }
        if (!count($topAdmins)) {
            return "";
        }
        return $topAdmins;
    }

    public function topAuthors()
    {
        $topAuthors = $this->authors()->orderBy('id', 'desc')->take(8)->get();
        return $topAuthors;
    }

    public function topFollowers()
    {
        $topFollows   = $this->follows()->orderBy('id', 'desc')->take(8)->get();
        $topFollowers = [];
        foreach ($topFollows as $follow) {
            $topFollowers[] = $follow->user;
        }
        return $topFollowers;
    }

    public function link()
    {
        return '<a href="/' . $this->name_en . '">' . $this->name . '</a>';
    }
}
