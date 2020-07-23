<?php

namespace Haxibiao\Question\Nova\Metrics\Curation;

use Haxibiao\Question\Curation;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class CurationPerDay extends Trend
{
    public $name = '每日纠题';
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

        $curations = Curation::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->where('created_at', '>=', now()->subDay($range - 1)->toDateString())
            ->groupBy('daily')->get();

        $curations->each(function ($curation) use (&$data) {
            $data[$curation->daily] = $curation->count;
        });

        if (count($data) < $range) {
            $data[today()->toDateString()] = 0;
        }

        $value           = end($data);
        $value_yesterday = null;
        if (count($data) >= 2) {
            $value_yesterday = max(array_slice($data, -2, 1));
        }
        $value_max = max($data);
        $values    = " 最大:$value_max 题, 昨日:$value_yesterday 题";

        return (new TrendResult($value))->trend($data)->suffix($values);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '最近30天内',
            7  => '最近7天内',
        ];
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
        return 'curation-per-day';
    }
}
