<?php

namespace App\Nova\Metrics;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;

class UserGender extends Partition
{
    public $name = '用户性别';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Profile::class, 'gender')->label(function ($value) {
            return in_array($value, User::getGenders()) ? $value : '未知';
        });
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-gender';
    }
}
