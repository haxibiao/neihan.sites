<?php

namespace Haxibiao\Question\Nova\Filters\Explanation;

use Haxibiao\Question\Explanation;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ExplanationTypeFilter extends Filter
{
    public $name = '解析类型筛选';

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
        return $query->where('type', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return array_flip(Explanation::getTypes());
    }
}
