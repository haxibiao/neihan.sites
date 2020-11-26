<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ArticleScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // 因为 article 存在一些多态的表关系, 所以这里使用 articles.id
        $builder->where('articles.id','>=', 32010);
    }
}
