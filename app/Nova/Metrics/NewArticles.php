<?php

namespace App\Nova\Metrics;

use App\Article;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Metrics\Value;

class NewArticles extends Value
{
    public $name = "每日新文章数";
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

        return (new TrendResult())->trend($data);
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
            'MTD' => '本月到目前为止',
            'QTD' => '本季到目前为止',
            'YTD' => '到今年为止',
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
        return 'new-articles';
    }
}
