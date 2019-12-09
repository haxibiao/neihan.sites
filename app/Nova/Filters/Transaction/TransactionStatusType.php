<?php

namespace App\Nova\Filters\Transaction;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransactionStatusType extends Filter
{
    public $name = '账单状态';
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
        return $query->where("type",$value);
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
              '打赏' => '打赏',
              '兑换' => '兑换',
              '提现' => '提现',
              '充值' => '充值',
        ];
    }
}
