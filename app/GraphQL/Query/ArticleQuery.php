<?php

namespace App\GraphQL\Query;

use App\Article;
use App\Visit;
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

        //在用户登录的情况下记录用户浏览记录
        if (checkUser()) {
            $user = getUser();            
            $visit = Visit::firstOrNew([
                'user_id'      => $user->id,
                'visited_type' => 'articles',
                'visited_id'   => $args['id'],
            ]);
            $visit->save();
        }

        return $article;
    }
}
