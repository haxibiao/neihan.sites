<?php

namespace Haxibiao\Question\Nova\Metrics\Question;

use App\Report;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class ReportQuestionsPerDay extends Trend
{
    public $name = '每日题目举报';
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

        $reports = Report::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->where('reportable_type', 'questions')
            ->where('created_at', '>=', now()->subDay($range)->toDateString())
            ->groupBy('daily')
            ->get();

        $reports->each(function ($report) use (&$data) {
            $data[$report->daily] = $report->count;
        });
        if (!isset($data[now()->toDateString()])) {
            $data[now()->toDateString()] = 0;
        }

        $value           = end($data);
        $value_yesterday = null;
        if (count($data) >= 2) {
            $value_yesterday = max(array_slice($data, -2, 1));
        }
        $value_max = max($data);
        $values    = " 最大:$value_max 举报, 昨日:$value_yesterday 举报";
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
        return now();
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-create-questions-count';
    }
}
