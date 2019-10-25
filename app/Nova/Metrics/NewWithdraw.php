<?php

namespace App\Nova\Metrics;

use App\Withdraw;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Laravel\Nova\Metrics\Value;

class NewWithdraw extends Trend
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

        $withdraws->each(function ($withdraw) use (&$data) {
            $data[$withdraw->daily] = $withdraw->count;
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
        return 'new-withdraw';
    }
}
