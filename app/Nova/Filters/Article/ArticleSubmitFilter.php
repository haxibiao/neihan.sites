<?php

namespace App\Nova\Filters\Article;

use App\Article;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ArticleSubmitFilter extends Filter
{
    public $name = '动态审核状态';


    public $component = 'select-filter';


    public function apply(Request $request, $query, $value)
    {
        return $query->where('submit', $value);
    }

    public function options(Request $request)
    {
        return array_flip(Article::getSubmitStatus());
    }
}
