<?php

namespace App\Nova\Metrics;

use App\User;
use Dotenv\Loader\Value;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Haxibiao\Content\Post;
use Laravel\Nova\Metrics\TrendResult;

class UserPostCount extends Trend
{
    public $name = '用户发布数趋势(最大/最小用户)';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $data  = [];
        $user = User::orderBy('count_posts','asc');
        $min = User::select('count_posts')->min('count_posts');
        $user->each(function ($user) use (& $data){
           $data[$user->name] = $user->count_posts;
        });
        $max = end($data);
        return (new TrendResult(end($data)))->trend($data)->suffix("最大数:$max 最小数:$min");
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            0 => '全部',
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
        return 'user-post-count';
    }
}
