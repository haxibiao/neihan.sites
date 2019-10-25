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
        $qb = new Follow();
        //user_id
        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }
        //recommend_for_user_id
        if (isset($args['recommend_for_user_id'])) {
            //不是你关注的，别人关注了的，就是可以推荐给你的
            //下面写法解决MySQL groupBy在Laravel5.3后的写法差异.对执行效率影响不大

            //排除当前用户关注的 将其余的关注Group
            $qb = $qb->select('followed_type', 'followed_id')
                ->where('user_id', '<>', $args['recommend_for_user_id'])
                ->groupBy('followed_type', 'followed_id');
        }
        //filter
        if (isset($args['filter'])) {
            switch ($args['filter']) {
                case 'USER':
                    $type = 'users';
                    break;

                case 'CATEGORY':
                    $type = 'categories';
                    break;

                case 'COLLECTION':
                    $type = 'collections';
                    break;
            }
            $qb = $qb->where('followed_type', $type);

            //排除掉其余关注里 当前用户已经关注的
            if (isset($args['recommend_for_user_id'])) {
                //排除当前用户已关注的
                $followIds = Follow::select('followed_id')
                    ->where('user_id', $args['recommend_for_user_id'])
                    ->where('followed_type', $type)
                    ->pluck('followed_id')->toArray();

                //如果是查找用户的话就排除当前用户
                if ($type == 'users') {
                    array_push($followIds, $args['recommend_for_user_id']);
                }

                $qb = $qb->whereNotIn('followed_id', $followIds);
            }

        }
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        $limit  = isset($args['limit']) ? $args['limit'] : 10; //默认10条历史记录

        $data = $qb->skip($offset)
            ->take($limit)
            ->get();

        //为了兼容前端返回不一致的 follow.id
        if (isset($args['recommend_for_user_id'])) {
            $index = $offset;
            foreach ($data as $item) {
                $item->id = $index++;
            }
        }

        return $data;
    }
}
