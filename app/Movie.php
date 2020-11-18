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
        $cate_id_cdn_map = [
            3 => 'https://cdn-youku-com.diudie.com/',
            1 => 'https://cdn-xigua-com.diudie.com/',
            2 => 'https://cdn-iqiyi-com.diudie.com/',
            4 => 'https://cdn-v-qq-com.diudie.com/',
        ];
        $cdn_root = $cate_id_cdn_map[$this->category_id] ?? null;

        return $cdn_root . $this->cover;

        //FIXME 修复缩略图和大图的关系，直接算出来
    }

    public function getRegionNameAttribute()
    {
        $cate_id_region_map = [
            3 => '韩剧',
            1 => '日剧',
            2 => '美剧',
            4 => '港剧',
        ];
        $region_name = $cate_id_region_map[$this->category_id] ?? null;
        return $region_name;
    }

    public function getPlayUrlAttribute()
    {

        return "http://cdn-iqiyi-com.diudie.com/series/70177/index.m3u8";
    }
}
