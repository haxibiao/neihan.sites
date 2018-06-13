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
            'user_id'               => ['name' => 'user_id', 'type' => Type::int()],
            'recommend_for_user_id' => ['name' => 'recommend_for_user_id', 'type' => Type::int()],
            'limit'                 => ['name' => 'limit', 'type' => Type::int()],
            'offset'                => ['name' => 'offset', 'type' => Type::int()],
            'filter'                => ['name' => 'filter', 'type' => GraphQL::type('FollowFilter')],
        ];
    }

    public function resolve($root, $args)
    {

        $qb = Follow::orderBy('id', 'desc'); //TODO:: consider distinct ...  这个留着后面推荐系统来解决

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']); //不是你关注的，别人关注了的，就是可以推荐给你的
        }

        if (isset($args['recommend_for_user_id'])) {
            $qb = $qb->where('user_id', '<>', $args['recommend_for_user_id']); //不是你关注的，别人关注了的，就是可以推荐给你的
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'USER') {
                $qb = $qb->where('followed_type', 'users');
            } else if ($args['filter'] == 'CATEGORY') {
                $qb = $qb->where('followed_type', 'categories');
            } else if ($args['filter'] == 'COLLECTION') {
                $qb = $qb->where('followed_type', 'collections');
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
