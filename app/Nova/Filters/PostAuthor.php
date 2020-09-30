<?php

namespace App\Nova\Filters;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PostAuthor extends Filter
{
    public $name = '文章作者';
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('user_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        $data = User::query()
            ->orderBy('name', 'DESC')
            ->pluck('id', 'name')
            ->toArray();
        return $data;

    }
}
