<?php

namespace App\Nova\Metrics;

use App\User;
use App\UserRetention;
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
        $range = $request->range;
        $startTime = today()->subDay($range + 1);
        $userIds = User::whereDate('created_at', $startTime)->count();
        if ($userIds <= 0) {
            return null;
        }
        $query = UserRetention::query();

        switch ($range) {
            case 1:
                $query->whereDate('next_day_retention_at', $startTime);
                break;
            case 3:
                $query->whereDate('third_day_retention_at', $startTime);
                break;
            case 5:
                $query->whereDate('fifth_day_retention_at', $startTime);
                break;
            case 7:
                $query->whereDate('sixth_day_retention_at', $startTime);
                break;
            case 30:
                $query->whereDate('month_retention_at', $startTime);
                break;
        }
        $userRetentionNum = $query->count();
        $result = sprintf('%.2f', ($userRetentionNum / $userIds) * 100);
        return $this->result($result)->suffix(' % (  ' . $userRetentionNum . ' :  ' . $userIds . ')');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => '次日留存率',
            3 => '三日留存率',
            5 => '五日留存率',
            7 => '七日留存率',
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
        //return now()->addMinutes(5);
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
