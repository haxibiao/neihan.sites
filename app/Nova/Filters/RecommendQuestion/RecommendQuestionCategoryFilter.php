<?php

namespace Haxibiao\Question\Nova\Filters\RecommendQuestion;

use Haxibiao\Question\Category;
use Haxibiao\Question\Question;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Filters\Filter;

class RecommendQuestionCategoryFilter extends BooleanFilter
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
        if ($value) {
            $value       = array_keys($value);
            $questionIds = Question::select('id')->whereIn('category_id', $value)->get()->pluck('id')->toArray();
            $query->whereIn('question_id', $questionIds)->latest('id')->take(1000);
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

        $categories = Category::select(['id', 'name'])->get();

        $result = [];
        foreach ($categories as $category) {
            $result[$category->name] = $category->id;
        }
        return $result;
    }
}
