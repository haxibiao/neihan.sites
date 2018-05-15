<?php

namespace App\GraphQL\Query;

use App\Question;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class QuestionsQuery extends Query
{
    protected $attributes = [
        'name' => 'questons',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Question'));
    }

    public function args()
    {
        return [
            'id'          => ['name' => 'id', 'type' => Type::int()],
            'user_id'     => ['name' => 'user_id', 'type' => Type::int()],
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],
            'limit'       => ['name' => 'limit', 'type' => Type::int()],
            'offset'       => ['name' => 'offset', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Question::where('id', $args['id'])->get();
        }

        $qb = Question::orderBy('id', 'desc');

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }

        if (isset($args['category_id'])) {
            $qb = $qb->where('category_id', $args['category_id']);
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);
        return $qb->get();
    }
}
