<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stickable extends Model
{
    protected $guarded = [];

    public function item()
    {
        return $this->morphTo('item');
    }

    public function getSubjectAttribute()
    {
        return $this->name;
    }

    public static function items($sticks)
    {
        $result = [];
        foreach ($sticks as $stick) {
            $result[] = $stick->item;
        }
        return $result;
    }

    public static function getNameByType($type){
        return [
            'movies' => [
                '视频页-电影'     => '视频页-电影',
                '首页-电影'       => '首页-电影',
            ],
            'posts' => [
                '首页-视频'      => '首页-视频',
            ],
            'articles' => [
                '首页-文章列表'  => '首页-文章列表',
            ],
            'categories' => [
                '首页-专题'      => '首页-专题',
                '视频页-视频专题' => '视频页-视频专题',
                '视频页-图解专题' => '视频页-图解专题',
            ],
            'collections' => [
                '视频页-热门合集' => '视频页-热门合集',
            ]
        ][$type];
    }
}
