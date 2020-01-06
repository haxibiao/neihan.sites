<?php

namespace App\Nova\Metrics;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class UserAppVersionPartition extends Partition
{
    public $name = '用户最后活跃App版本号分布';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $data = [];
        $profiles = Profile::selectRaw(" app_version as version,count(*) as count ")
            ->groupBy('version')->get();
        $profiles->each(function ($profile) use (&$data) {
            if(!$profile->version){
                $data['未知'] = $profile->count;
            } else {
                $data[$profile->version] = $profile->count;
            }
        });
        return new PartitionResult($data);
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
        return 'user-app-version-partition';
    }
}
