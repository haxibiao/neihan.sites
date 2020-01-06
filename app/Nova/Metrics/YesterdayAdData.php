<?php

namespace App\Nova\Metrics;

use App\RewardCounter;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Metrics\Partition;
use phpDocumentor\Reflection\Types\Integer;

class YesterdayAdData extends Partition
{
    public $name = '昨日广告数';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $date = [today()->subDay(), today()];
        $adName     = ['count_toutiao', 'count_tencent', 'count_baidu'];
        $dimensions = RewardCounter::query()->select(\DB::raw('sum(count_toutiao) as "头条广告",sum(count_tencent) as
         "腾讯广告",sum(count_baidu) as "百度广告"'))->whereBetWeen('created_at', $date)->get()->toArray();
        //  foreach($dimensions as $dimension){
           
        //     $data['name'][] = $cate ? $cate->name : '空';
        //     $data['data'][] = $top_category["categoryCount"];
        // }
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
        return 'yesterday-ad-data';
    }
}
