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
        $qb = Follow::orderBy('id', 'desc');
        //user_id
        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }
        //recommend_for_user_id
        if (isset($args['recommend_for_user_id'])) {
            //不是你关注的，别人关注了的，就是可以推荐给你的
            $qb = $qb->where('user_id', '<>', $args['recommend_for_user_id'])
                ->groupBy('followed_type','followed_id'); 
        }
        //filter
        if (isset($args['filter'])) {
            switch ($args['filter']) { 
                case 'USER':
                    $qb = $qb->where('followed_type', 'users');
                    break;

                case 'CATEGORY':
                    $qb = $qb->where('followed_type', 'categories');
                    break;

                case 'COLLECTION':
                    $qb = $qb->where('followed_type', 'collections');
                    break;
            }
        } 
        $offset = isset($args['offset'])? $args['offset']: 0;
        $limit  = isset($args['limit'])? $args['limit']  : 10;//默认10条历史记录

        return $qb->skip($offset)
            ->take($limit)
            ->get();
    }
}
