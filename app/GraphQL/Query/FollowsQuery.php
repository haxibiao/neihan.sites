<?php

namespace App\GraphQL\Query;

use App\Follow;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class FollowsQuery extends Query
{
    protected $attributes = [
        'name' => 'follows',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Follow'));
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'offset'  => ['name' => 'offset', 'type' => Type::int()],
            'filter'  => ['name' => 'filter', 'type' => GraphQL::type('FollowFilter')],
        ];
    }

    public function resolve($root, $args)
    {

        $qb = Follow::orderBy('id', 'desc'); //consider distinct ...

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', '<>', $args['user_id']);
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'USER') {
                $qb = $qb->where('followed_type', 'users');
            } elseif ($args['filter'] == 'CATEGORY') {
                $qb = $qb->where('followed_type', 'categories');
            }
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }

        $qb = $qb->take($limit * 5);
        //take $limit*5
        $follows = $qb->get();
        $follows = $follows->unique(function ($item) {
            return $item['followed_type'] . $item['followed_id'];
        });
        $follows = $follows->take(10)->all();
        return $follows;
    }
}
