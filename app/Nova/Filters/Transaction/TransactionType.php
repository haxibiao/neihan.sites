<?php

namespace App\Nova\Filters\Transaction;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransactionType extends Filter
{
    public $name = '账单类型';
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where("status",$value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            '未支付' => '未支付',
            '已支付' => '已支付',
            '已兑换' => '已兑换',
        ];
    }
}
