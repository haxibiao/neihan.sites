<?php

namespace App;

use App\Model;

class Collection extends Model
{
    public $fillable = [
        'user_id',
        'status',
        'type',
        'name',
        'logo',
        'count_words',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function hasManyArticles()
    {
        return $this->hasMany(\App\Article::class)->where('status', '>=', '0');
    }

    public function follows()
    {
        return $this->morphMany(\App\Follow::class, 'followed');
    }

    public function logo()
    {
        $path = empty($this->logo) ? '/images/collection.png' : $this->logo;
        if (file_exists(public_path($path))) {
            return $path;
        }
        return env('APP_URL') . $path;
    }
    public function allArticles(){ 
        return $this->belongsToMany(\App\Article::class)->where('articles.status', '>=', 0);
    }
}
