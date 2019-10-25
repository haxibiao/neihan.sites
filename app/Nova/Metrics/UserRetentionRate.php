<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class UserRetentionRate extends Value
{
    public $name = "用户留存率";
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $startTime = today()->subDay($request->range);
        $endTime   = today();

        $qb_new_users    = User::whereBetween('created_at', [$startTime->toDateString(), $startTime->addDay()->toDateString()]);
        $new_users_count = $qb_new_users->count();

        $qb_keep_users    = $qb_new_users->whereBetween('updated_at', [$endTime->toDateString(), $endTime->addDay()->toDateString()]);
        $keep_users_count = $qb_keep_users->count();
        $result           = 0;

        if ($new_users_count) {
            $result = ceil($keep_users_count * 100 / $new_users_count);
        }

        return $this->result($result)->suffix("%( $keep_users_count : $new_users_count )");
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1  => '次日留存率',
            2  => '两日留存率',
            3  => '三日留存率',
            5  => '五日留存率',
            7  => '七日留存率',
            30 => '三十日留存率',
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
        return 'user-retention-rate';
    }
}
