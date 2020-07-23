<?php

namespace Haxibiao\Question\Nova\Filters\Category;

use Haxibiao\Question\Tag;
use Haxibiao\Question\Taggable;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CategoryTagFilter extends Filter
{
    public $name = '分类标签筛选';

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
        $cIds = Taggable::where('tag_id', $value)->where('taggable_type', 'categories')->get()->pluck('taggable_id');
        return $query->whereIn('id', $cIds);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return array_flip(Tag::all()->pluck('name', 'id')->toArray());
    }
}
