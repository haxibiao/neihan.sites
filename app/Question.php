<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'background',
    ];

    public function articles()
    {
        return $this->belongsToMany(\App\Article::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Answer::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }

    public function latestAnswer()
    {
        return $this->belongsTo(\App\Answer::class, 'latest_answer_id');
    }

    public function bestAnswer()
    {
        return $this->belongsTo(\App\Answer::class, 'best_answer_id');
    }

    public function relateImage()
    {
        //有最新回答，先用回答里的图片
        if ($this->latestAnswer && !empty($this->latestAnswer->image_url)) {
            return $this->latestAnswer->image_url;
        }
        //没有，只好用问题里的图片
        if (!empty($this->image1)) {
            return $this->image1;
        }
        return null;
    }
}
