<?php

namespace App\Nova\Filters\Article;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArticleStatusType extends Filter
{
    public $name = '文章状态';
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
        return $query->where('status', $value);
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
            '公开' => '1',
            '草稿' => '0',
            '下架' => '-1',
        ];
    }
}
