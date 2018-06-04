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
        if (starts_with($this->path, 'http')) {
            return $this->path;
        }
        return file_exists(public_path($this->path)) ? url($this->path) : env('APP_URL') . $this->path;
    }

    public function cover()
    {
        $cover_url = "";
        if (!empty($this->cover)) {
            $cover_url = file_exists(public_path($this->cover)) ? url($this->cover) : env('APP_URL') . $this->cover;
        }
        return $cover_url;
    }

    public function covers()
    {
        $covers = [];
        for ($i = 1; $i <= 8; $i++) {
            $cover = $this->cover . "." . $i . ".jpg";
            if (file_exists(public_path($cover))) {
                $covers[] = $cover;
            }
        }
        return $covers;
    }

    public function takeSnapshot($force = false)
    {
        \App\Jobs\videoCapture::dispatch($this, $force);
    }

    public function fillForJs()
    {

    }
}
