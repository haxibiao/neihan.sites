<?php

namespace App\Nova\Filters\Transaction;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class WalletType extends Filter
{
    public $name = '钱包类型';
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
            'RMB钱包' => '0',
            '金币钱包' => '1',
        ];
    }
}
