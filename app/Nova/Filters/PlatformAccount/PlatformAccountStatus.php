<?php

namespace App\Nova\Filters;

use App\PlatformAccount;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PlatformAccountStatus extends Filter
{
    public $name = '账号状态筛选';
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
        return $query->where('order_status', $value);
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
            '未使用' => PlatformAccount::UNUSE,
            '使用中' => PlatformAccount::INUSE,
            '已过期' => PlatformAccount::EXPIRE,
        ];
    }
}
