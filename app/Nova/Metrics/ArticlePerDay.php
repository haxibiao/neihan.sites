<?php

namespace App\Nova\Metrics;

use App\Article;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class ArticlePerDay extends Trend
{

    public $name = "每日新增文章数";

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

        $post = Article::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->where('created_at', '>=', now()->subDay($range - 1)->toDateString())
            ->whereIn('type',['issue','post'])
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
            7  => '7天之内',
            30 => '30 天之内',
            60 => '60 天之内',
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
        return 'article-per-day';
    }
}
