<?php

namespace App\Nova\Filters\Content;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TaskStatusType extends Filter
{
    public $name = '专题状态';

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
        return $query->where('status',$value);
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
            '上架' => 1,
            '隐藏' => 0,
        ];
    }
}
