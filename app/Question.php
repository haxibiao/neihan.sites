<?php

namespace App;

use App\Image;
use App\Model;

class Question extends Model
{
    public $fillable = [
        'user_id',
        'title',
        'background',
        'is_anonymous',
        'bonus',
        'deadline',
        'image1',
        'image2',
        'image3',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Answer::class)->orderBy('id', 'desc')->where('status', '>=', 0);
    }

    public function latestAnswer()
    {
        return $this->belongsTo(\App\Answer::class, 'latest_answer_id')->where('status','>=',0);
    }

    public function bestAnswer()
    {
        return $this->belongsTo(\App\Answer::class, 'best_answer_id');
    }

    public function isPay()
    {
        return $this->bonus > 0;
    }

    public function leftHours()
    {
        $left = 48;
        $left = $this->created_at->addDays($this->deadline)->diffInHours(now());
        return $left;
    }

    public function isAnswered()
    {
        return !empty($this->selectedAnswers());
    }

    public function selectedAnswers()
    {
        $answers = [];
        if (!empty($this->answered_ids)) {
            $answered_ids = explode(',', $this->answered_ids);
            $answers    = $this->answers()->whereIn('id', $answered_ids)->get();
        }
        return $answers;
    }

    public function relateImage()
    {
        //有最新回答，先用回答里的图片
        if ($this->latestAnswer && !empty($this->latestAnswer->image_url)) {
            $image_url = $this->latestAnswer->image_url;
            $image     = Image::where('path', $image_url)->first();
            if ($image) {
                $image_url = $image->url_small();
            }
            return $image_url;
        }
        //没有，只好用问题里的图片
        if (!empty($this->image1)) {
            //多用於列表，都用小圖
            $image_url = $this->image1;
            $image     = Image::where('path', $image_url)->first();
            if ($image) {
                $image_url = $image->url_small();
            }
            return $image_url;
        }
        return null;
    }

    public function image1()
    {
        if (\App::environment('local')) {
            if ($image = Image::where('path', $this->image1)->first()) {
                return $image->url_prod();
            }
        }
        return $this->image1;
    }

    public function image2()
    {
        if (\App::environment('local')) {
            if ($image = Image::where('path', $this->image2)->first()) {
                return $image->url_prod();
            }
        }
        return $this->image2;
    }

    public function image3()
    {
        if (\App::environment('local')) {
            if ($image = Image::where('path', $this->image3)->first()) {
                return $image->url_prod();
            }
        }
        return $this->image3;
    }

    public function link()
    {
        return '<a href="/question/' . $this->id . '">' . $this->title . '</a>';
    }
    public function result()
    {
        return $this->answered_ids ? '已经结账' : '无人回答已经退回余额';
    }

}
