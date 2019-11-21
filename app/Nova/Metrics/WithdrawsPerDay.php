<?php

namespace App\Nova\Metrics;

use App\Withdraw;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class WithdrawsPerDay extends Trend
{
    public $name = "每日提现成功笔数";
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

        $withdraws = Withdraw::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,count(*) as count ")
            ->where('created_at', '>=', now()->subDay($range - 1)->toDateString())
            ->groupBy('daily')->get();

        $withdraws->each(function ($curation) use (&$data) {
            $data[$curation->daily] = $curation->count;
        });

        $value = end($data);
        return (new TrendResult($value))->trend($data);
    }
    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7   => '过去七天内',
            30  => '过去30天内',
            60  => '过去60天内',
            365 => '过去365天内',
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
        return 'withdraws-per-day';
    }
}
