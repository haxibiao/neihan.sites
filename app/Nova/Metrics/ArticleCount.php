<?php

namespace App\Nova\Metrics;

use App\Article;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class ArticleCount extends Value
{

    public $name = '视频动态总数(个)';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        if($request->range=='0'){
            return $this->result(\App\Article::whereNotNull('video_id')->whereIn('type',['post','video'])->count());    

        }else{
            return $this->count($request, \App\Article::whereNotNull('video_id')->whereIn('type',['post','video']));
        }
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            0 => '全部',
            //30 => '一个月之内',
            //365 => '一年之内'
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'article-count';
    }
}
