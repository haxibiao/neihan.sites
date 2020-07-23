<?php

namespace Haxibiao\Question\Nova\Filters\Category;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Category;

class CategoryQuestionsCountOrder extends Filter
{
    public $name = '题目数量排序';

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
        return $query->orderBy('questions_count', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Category::getOrders();
    }
}
