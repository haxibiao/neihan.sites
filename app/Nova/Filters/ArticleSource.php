<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArticleSource extends Filter
{

    public $name = '文章出处';

    public $component = 'select-filter';

    public function apply(Request $request, $query, $value)
    {
        if ($value == 'douyin') {
            return $query->where('source_url', 'like', '%douyin%');
        } else if ($value == 'user') {
            return $query->whereNull('source_url');
        }
    }

    public function options(Request $request)
    {
        return [
            '抖音'   => 'douyin',
            '用户发布' => 'user',
        ];
    }
}
