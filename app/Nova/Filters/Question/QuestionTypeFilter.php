<?php

namespace Haxibiao\Question\Nova\Filters\Question;

use Haxibiao\Question\Question;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class QuestionTypeFilter extends BooleanFilter
{
    public $name = '类型';
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
        if (count($value)) {
            return $query->whereIn('type', $value);
        } else {
            return $query;
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return array_flip(Question::getTypes());
    }
}
