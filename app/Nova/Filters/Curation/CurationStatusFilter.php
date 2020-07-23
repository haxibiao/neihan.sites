<?php

namespace Haxibiao\Question\Nova\Filters\Curation;

use Haxibiao\Question\Curation;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;


class CurationStatusFilter extends Filter
{
    public $name = '审核状态';
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
        return array_flip(Curation::getStatuses());
    }
}
