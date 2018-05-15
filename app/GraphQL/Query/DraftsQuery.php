<?php

namespace App\GraphQL\Query;

use App\Article;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class DraftsQuery extends Query
{
    protected $attributes = [
        'name' => 'drafts',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Article'));
    }

    public function args()
    {
        return [
            'first' => ['name' => 'first', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $user = session('user');
        if (!$user) {
            throw new \Exception('user unauthorized');
        }

        $qb = Article::where('status', 0)->where('user_id', $user->id);

        $first = 10;
        if (isset($args['first'])) {
            $first = $args['first'];
        }
        $qb = $qb->take($first);

        return $qb->get();
    }
}
