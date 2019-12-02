<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class NewUsers extends Value
{

    public $name = "每日新用户数";

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        if ($request->range == 0) {
            $date = [
                date('Y-m-d'),
                date('Y-m-d', strtotime("+1 day")),
            ];
        }

        //自定义设置
        if (isset($date)) {
            $valueResult = $this->result($this->getCustomDateUserCount($date));
        } else {
            $valueResult = $this->count($request, \App\User::class);
        }
        return $valueResult->suffix('new user');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            0     => date('Y-m-d'),
            7     => '过去七天内',
            30    => '过去30天内',
            60    => '过去60天内',
            365   => '过去365天内',
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
        return 'new-users';
    }

    public function getCustomDateUserCount(array $date)
    {
        return \App\User::query()->whereBetween('created_at', $date)->count();
    }
}
