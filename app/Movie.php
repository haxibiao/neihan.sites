<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    public $connection = 'nhdy';

    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    public function getCoverUrlAttribute()
    {
        return "https://cos-study.jinlinle.com/" . $this->cover;
    }

    public function getPlayUrlAttribute()
    {

        return "http://cdn-iqiyi-com.diudie.com/series/70177/index.m3u8";
    }
}
