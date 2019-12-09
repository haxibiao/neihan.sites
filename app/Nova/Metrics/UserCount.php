<?php

namespace App\Nova\Metrics;

use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class UserCount extends Value
{

    public $name = '用户总数(位)';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        if($request->range=='0'){
             return  $this->result(\App\User::query()->count());
        }else{
             return  $this->count($request,\App\User::class);
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
            //365 => '一年之内',
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
        return 'user-count';
    }
}
