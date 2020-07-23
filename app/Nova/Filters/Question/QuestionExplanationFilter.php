<?php

namespace Haxibiao\Question\Nova\Filters\Question;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class QuestionExplanationFilter extends BooleanFilter
{
    public $name = '题目解析';

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

        $value = array_filter($value, function ($v) {
            return $v;
        });
        $value = array_keys($value);
        if (count($value) == 1) {
            if ($value[0] == "无解析") {
                return $query->whereNull('explanation_id');
            }
            return $query->whereNotNull('explanation_id');
        }
        return $query;
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
            '有解析' => '有解析',
            '无解析' => '无解析',
        ];
    }
}
