<?php

namespace App\GraphQL\Query;

use App\Category;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class CategoriesQuery extends Query
{
    protected $attributes = [
        'name' => 'categories',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Category'));
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
            'filter'  => ['name' => 'filter', 'type' => GraphQL::type('CategoryFilter')],
            'offset'  => ['name' => 'offset', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'keyword'     => ['name' => 'keyword'   , 'type'    => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $qb = Category::orderBy('id', 'desc');
        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }

        if (isset($args['keyword'])) {
            $keyword = trim($args['keyword']);
            if( empty( $keyword ) ){
                return null;
            } 

            $qb = Category::orderBy('name', 'asc');
            $qb = $qb->where('name', 'like', "%$keyword%")
                ->where('status', 1);
            $results = $qb->count();
            //记录用户搜索日志
            if ( $results ) {
                //保存全局搜索
                $query_item = \App\Query::firstOrNew([
                    'query' => $keyword,
                ]);
                $query_item->results = $results;
                $query_item->hits++;
                $query_item->save();

                //保存个人搜索,游客也进行记录
                $query_log = \App\Querylog::firstOrNew([
                    'user_id' => checkUser() ? getUser()->id : null,
                    'query'   => $keyword,
                ]);
                $query_log->save();
            }
        }

        if (isset($args['filter'])) {
            if ($args['filter'] == 'SPECIAL') {
                $qb = $qb->where('is_for_app', 1);
            }

            if ($args['filter'] == 'ADMIN') {
                if (!isset($args['user_id'])) {
                    throw new \Exception('查看用户管理的专题必须提供user_id');
                }
                $user = \App\User::findOrFail($args['user_id']);
                $qb   = $user->adminCategories();
            }

            if ($args['filter'] == 'FOLLOWED') {
                if (!isset($args['user_id'])) {
                    throw new \Exception('查看用户follow的专题必须提供user_id');
                }
                $user = \App\User::findOrFail($args['user_id']);
                $qb   = $user->followings()->where('followed_type', 'categories');
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
            $categories = [];
            foreach ($qb->get() as $follow) {
                $categories[] = $follow->followed;
            }
            return $categories;
        }
        return $qb->get();
    }
}
