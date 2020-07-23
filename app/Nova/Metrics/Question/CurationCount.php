<?php

namespace Haxibiao\Question\Nova\Metrics\Question;

use Haxibiao\Question\Curation;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

class CurationCount extends Value
{
    public $name = '纠题总数';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Curation::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'YTD'   => '2019年',
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
        return 'curation-count';
    }
}
