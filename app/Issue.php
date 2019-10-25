<?php

namespace App;



use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use SoftDeletes;

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

    public function article(){
        return $this->hasOne(\App\Article::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Category::class);
    }

    public function resolutions()
    {
        return $this->hasMany(\App\Resolution::class)->orderBy('id', 'desc');
    }

    public function latestResolution()
    {
        return $this->belongsTo(\App\Resolution::class, 'latest_resolution_id');
    }

    public function bestResolution()
    {
        return $this->belongsTo(\App\Resolution::class, 'best_resolution_id');
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
        if (!empty($this->resolution_ids)) {
            $resolution_ids = explode(',', $this->resolution_ids);
            $answers    = $this->resolutions()->whereIn('id', $resolution_ids)->get();
        }
        return $answers;
    }

    public function relateImage()
    {
        //有最新回答，先用回答里的图片
        if ($this->latestResolution && !empty($this->latestResolution->image_url)) {
            $image_url = $this->latestResolution->image_url;
            $image     = Image::where('path', $image_url)->first();
            if ($image) {
                $image_url = $image->thumbnail;
            }
            return $image_url;
        }
        //没有，只好用问题里的图片
        if (!empty($this->image1)) {
            //多用於列表，都用小圖
            $image_url = $this->image1;
            $image     = Image::where('path', $image_url)->first();
            if ($image) {
                $image_url = $image->thumbnail;
            }
            info($image_url);
            return $image_url;
        }
        return null;
    }

    /**
     * @deprecated
     */
    public function image1()
    {
        return $this->image1;
    }

    /**
     * @deprecated
     */
    public function image2()
    {
        return $this->image2;
    }

    /**
     * @deprecated
     */
    public function image3()
    {
        return $this->image3;
    }

    public function link()
    {
        return '<a href="/issue/' . $this->id . '">' . $this->title . '</a>';
    }
    public function result()
    {
        return $this->resolution_ids ? '已经结账' : '无人回答已经退回余额';
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable', 'imageable');
    }
}
