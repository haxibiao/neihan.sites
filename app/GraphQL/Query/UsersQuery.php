<?php

namespace App\GraphQL\Query;

use App\Category;
use App\Collection;
use App\User;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'users',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'user_id'     => ['name' => 'user_id', 'type' => Type::int()],
            'email'       => ['name' => 'email', 'type' => Type::string()],
            'name'        => ['name' => 'name', 'type' => Type::string()],
            'category_id' => ['name' => 'category_id', 'type' => Type::int()],
            'collection_id' => ['name' => 'collection_id', 'type' => Type::int()],
            'limit'       => ['name' => 'limit', 'type' => Type::int()],
            'offset'      => ['name' => 'offset', 'type' => Type::int()],
            'filter'      => ['name' => 'filter', 'type' => GraphQL::type('UserFilter')],
        ];
    }

    public function resolve($root, $args)
    {
        $qb = User::orderBy('id', 'desc');

        if (isset($args['email'])) {
            $qb = $qb->where('email', $args['email']);
        }

        if (isset($args['name'])) {
            $qb = $qb->where('name', $args['name']);
        }

        if (isset($args['collection_id'])) {
            $collection = Collection::findOrFail($args['collection_id']);
            $qb         = $collection->follows();
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'RECOMMEND') {
                $qb = $qb->orderBy('count_articles', 'desc');
            }
            if ($args['filter'] == 'FOLLOWINGS') {
                if (!isset($args['user_id'])) {
                    throw new Exception('查看用户的关注必须提供user_id');
                }
                $user = \App\User::findOrFail($args['user_id']);
                $qb   = $user->followingUsers();
            }
            if ($args['filter'] == 'FOLLOWERS') {
                if (!isset($args['user_id'])) {
                    throw new Exception('查看用户的粉丝必须提供user_id');
                }
                $user = \App\User::findOrFail($args['user_id']);
                $qb   = $user->follows();
            }

        }

        if (isset($args['category_id'])) {
            $category = Category::findOrFail($args['category_id']);
            if (isset($args['filter']) && $args['filter'] == "CATE_ADMINS") {
                $qb = $category->admins();
            }
            if (isset($args['filter']) && $args['filter'] == "CATE_AUTHORS") {
                $qb = $category->authors();
            }
            if (isset($args['filter']) && $args['filter'] == "CATE_FOLLOWERS") {
                $qb = $category->follows();
            }
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);

        if (isset($args['filter'])) {
            if ($args['filter'] == 'RECOMMEND') {
                $qb = $qb->orderBy('count_articles', 'desc');
            }
            if ($args['filter'] == 'FOLLOWINGS') {
                $users = [];
                foreach ($qb->get() as $follow) {
                    $users[] = $follow->followed;
                }
                return $users;
            }
            if ($args['filter'] == 'FOLLOWERS') {
                $users = [];
                foreach ($qb->get() as $follow) {
                    $users[] = $follow->user;
                }
                return $users;
            }
        }

        if (isset($args['category_id'])) {
            if (isset($args['filter']) && $args['filter'] == "CATE_FOLLOWERS") {
                $users = [];
                foreach ($qb->get() as $follow) {
                    $users[] = $follow->user;
                }
                return $users;
            }
        }

        if (isset($args['collection_id'])) {
            $users = [];
            foreach ($qb->get() as $follow) {
                $users[] = $follow->user;
            }
            return $users;
        }

        return $qb->get();
    }
}
