<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ArticleSubmitScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('submit', 1)->where('status', 1)->where('video_id', '<>', 1);
    }
}
