<?php

namespace App\Nova\Metrics;

use App\Dimension;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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
        // $cacheKey = 'nova_user_activity_num_of_%s';
        $endOfDay = Carbon::yesterday();
        $headOfDay = Carbon::yesterday()->addDay(1);
        for ($i = 0; $i < $range - 1; $i++) {

            // $key   = sprintf($cacheKey, $endOfDay->toDateString());
            // $cacheValue = cache()->store('database')->get($key, 0);
            $dimension = Dimension::where("created_at", "<", $headOfDay)->where("created_at", ">=", $endOfDay)->where("name", "日活数")->first();
            $count = 0;
            if ($dimension) {
                $count = $dimension->count;
            }
            $data[$endOfDay->toDateString()] = $count;
            $endOfDay = $endOfDay->subDay(1);
            $headOfDay = $headOfDay->subDay(1);
        }
        // //实时获取今日的用户活跃数
        // $count  = Visit::whereDate('created_at',today())->distinct()->count('user_id');
        $data = array_reverse($data);
        // $data = array_merge($data,[today()->toDateString() => $count]);

        return (new TrendResult(Arr::last($data)))->trend($data);
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
        return now();
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
