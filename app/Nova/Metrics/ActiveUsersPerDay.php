<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class ActiveUsersPerDay extends Trend
{

    public $name = '每日活跃用户趋势(位)';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $range = $request->range;
        $data  = [];

        //没有数据的日期默认值为0
        for($j=$range-1;$j>=0;$j--){
            $intervalDate = date('Y-m-d',strtotime(now().'-'.$j.'day'));
            $data[$intervalDate] = 0;
        }

        $post = User::selectRaw(" distinct(date_format(updated_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->whereDate('updated_at', '>=', now()->subDay($range - 1))
            ->groupBy('daily')->get();

        $post->each(function ($post) use (&$data) {
            $data[$post->daily] = $post->count;
        });

        if (count($data) < $range) {
            $data[now()->toDateString()] = 0;
        }

        return (new TrendResult(end($data)))->trend($data);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7   => '过去7天内',
            30  => '过去30天内',
            60  => '过去60天内',
            90  => '过去90天内',
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
        return 'active-users-per-day';
    }
}
