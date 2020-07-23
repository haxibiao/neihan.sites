<?php

namespace Haxibiao\Question\Nova\Filters\Category;

use Haxibiao\Question\Category;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CategorySubmitFilter extends Filter
{
    public $name = '分类出题筛选';

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
        return $query->where('allow_submit', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return array_flip(Category::getAllowSubmits());
    }
}
