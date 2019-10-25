<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class UsersPerDay extends Trend
{

    public $name = '每日新增用户趋势';
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

        $users = User::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->where('created_at', '>=', now()->subDay($range - 1)->toDateString())
            ->groupBy('daily')->get();

        $users->each(function ($user) use (&$data) {
            $data[$user->daily] = $user->count;
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
            7  => '7天之内',
            30 => '30 天之内',
            60 => '60 天之内',
            90 => '90 天之内',
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
        return 'users-per-day';
    }
    public function getCustomDateUserCount(array $date)
    {
        return User::query()->whereBetween('created_at', $date)->count();
    }
}
