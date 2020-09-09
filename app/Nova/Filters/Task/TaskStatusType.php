<?php

namespace App\Nova\Filters\Task;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TaskStatusType extends Filter
{
    public $name = '任务状态';

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
            '已展示' => \App\Task::ENABLE,
            '未展示' => \App\Task::DISABLE,
        ];
    }
}
