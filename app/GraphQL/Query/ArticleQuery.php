<?php

namespace App\GraphQL\Query;

use App\Article;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ArticleQuery extends Query
{
    protected $attributes = [
        'name'        => 'Article',
        'description' => 'return a Article',
    ];

    public function type()
    {
        return GraphQL::type('Article');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $article       = Article::findOrFail($args['id']);
        $article->hits = $article->hits + 1;
        $article->save();
        return $article;
    }
}
