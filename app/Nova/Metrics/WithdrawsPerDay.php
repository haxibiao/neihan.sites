<?php

namespace App\Nova\Metrics;

use App\Withdraw;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;
use Illuminate\Support\Arr;

class WithdrawsPerDay extends Trend
{
    public $name = "每日成功提现趋势(元)";
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

        //没有数据的日期默认值为0
        for($j=$range-1;$j>=0;$j--){
            $intervalDate = date('Y-m-d',strtotime(now().'-'.$j.'day'));
            $data[$intervalDate] = 0;
        }
        
        $withdraws = Withdraw::selectRaw(" distinct(date_format(created_at,'%Y-%m-%d')) as daily,sum(amount) as count ")
            ->whereDate('created_at', '>=', now()->subDay($range - 1 ))
            ->where('status',Withdraw::SUCCESS_WITHDRAW)
            ->groupBy('daily')->get();
        $withdraws->each(function ($curation) use (&$data) {
            $data[$curation->daily] = $curation->count;
        });
        if (count($data) < $range) {
            $data[now()->toDateString()] = 0;
        }

        $max       = max($data);
        $yesterday = array_values($data)[$range-2];

        return (new TrendResult(Arr::last($data)))->trend($data)->suffix("昨日: $yesterday  最大: $max");
    }
    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7   => '过去7天内',
            30  => '过去30天内',
            60  => '过去60天内',
            90  => '过去90天内',
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
