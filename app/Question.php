<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'background',
        'image1',
        'image2',
        'image3',
        'deadline',
        'is_anonymous',
    ];

    protected $dates = ['deadline'];

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

    public function user()
    {
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

        //实在没有 只能凉了
        return 0;
    }

    //check question has img

    public function has_img()
    {
        return $this->relateImage() ? 'have_img' : '';
    }

    public function count_defalut()
    {
        $this->count_answers   = $this->count_answers > 0 ? $this->count_answers : 0;
        $this->count_favorites = $this->count_favorites > 0 ? $this->count_favorites : 0;
        $this->count_comments  = $this->count_comments > 0 ? $this->count_comments : 0;
        $this->count_likes     = $this->count_likes > 0 ? $this->count_likes : 0;
    }

    public function link()
    {
        return '<a href="/question/' . $this->id . '">' . $this->title . '</a>';
    }
}
