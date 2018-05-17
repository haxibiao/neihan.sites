<?php

namespace App;

use App\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'path',
        'path_mp4',
        'cover',
        'introduction',
        'duration',
        'hash',
        'adstime',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function comments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable');
    }

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function likes()
    {
        return $this->morphMany(\App\Like::class, 'liked');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function tips()
    {
        return $this->morphMany(\App\Tip::class, 'tipable');
    }

    //computed

    public function source()
    {
        return file_exists(public_path($this->path)) ? url($this->path) : env('APP_URL') . $this->path;
    }

    public function cover()
    {
        return file_exists(public_path($this->cover)) ? url($this->cover) : env('APP_URL') . $this->cover;
    }

    public function fillForJs()
    {

    }
}
