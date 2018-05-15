<?php

namespace App\GraphQL\Query;

use App\Collection;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class CollectionsQuery extends Query
{
    protected $attributes = [
        'name' => 'Collections',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Collection'));
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
            'offset'  => ['name' => 'offset', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'filter'  => ['name' => 'filter', 'type' => GraphQL::type('CollectionFilter')],
        ];
    }

    public function resolve($root, $args)
    {
        // $qb = Collection::orderBy('order', 'desc')->orderBy('id', 'desc');
        $qb = Collection::orderBy('id', 'desc');

        if (isset($args['filter']) && $args['filter'] == 'FOLLOWED') {
            if (!isset($args['user_id'])) {
                throw new Exception('查看用户关注的文集必须提供user_id');
            }
            $user = \App\User::findOrFail($args['user_id']);
            $qb   = $user->followings()->where('followed_type', 'collections');
        } else {
            if (isset($args['user_id'])) {
                $qb = $qb->where('user_id', $args['user_id']);
            }
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 100;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);

        if (isset($args['filter']) && $args['filter'] == 'FOLLOWED') {
            $collections = [];
            foreach ($qb->get() as $follow) {
                $collections[] = $follow->followed;
            }
            return $collections;
        }
        return $qb->get();
    }
}
