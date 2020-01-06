<?php

namespace App\Nova\Metrics;

use App\RewardCounter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Partition;

class TodayAdData extends Partition
{
    public $name = '今日广告数';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        //$date = [date("Y-m-d",strtotime("+1 seconds")),date("Y-m-d",strtotime("+1 day"))];
        $adName     = ['count_toutiao', 'count_tencent', 'count_baidu'];
        $dimensions = RewardCounter::query()->select(\DB::raw('sum(count_toutiao) as "头条广告",sum(count_tencent) as "腾讯广告",sum(count_baidu) as "百度广告"'))->where('created_at',">",today() )->get()->toArray();
     
        foreach($dimensions as $dimension){
            $dimension["头条广告"] = (int)$dimension["头条广告"];
            $dimension["腾讯广告"] = (int)$dimension["腾讯广告"];
            $dimension["百度广告"] = (int)$dimension["百度广告"];
            return $this->result($dimension);
        }
        return null;
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
        return 'today-ad-data';
    }
}
