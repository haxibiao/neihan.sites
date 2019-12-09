<?php

namespace App\Nova\Filters\Other;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TaskType extends Filter
{
    public $name = '任务类型';  

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
        return $query->where('type',$value);
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
              '新人任务' => \App\Task::NEW_USER_TASK,
              '每日任务' =>  \App\Task::DAILY_TASK,
              '自定义任务' => \App\Task::CUSTOM_TASK,
        ];
    }
}
