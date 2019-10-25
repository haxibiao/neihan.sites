<?php

namespace App\Nova\Filters\Article;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArticleType extends Filter
{

    public $name = '文章类型';
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
        if ($value == "post") {
            return $query->where('type', 'post')->whereNull('video_id');
        }
        if ($value == "videoPost") {
            return $query->whereIn('type', ['post', 'video'])->whereNotNull('video_id');
        }

        if ($value == "issue") {
            return $query->where('type', 'issue');
        }

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
        return [
            '图文动态' => 'post',
            '视频动态' => 'videoPost',
            '有奖问答' => 'issue',
        ];
    }
}
