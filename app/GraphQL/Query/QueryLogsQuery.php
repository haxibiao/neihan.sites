<?php

namespace App\GraphQL\Query;

use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class QueryLogsQuery extends Query
{
    protected $attributes = [
        'name'        => 'QueryLogs',
        'description' => 'QueryLog list',
    ];
    public function args()
    {
        return [
            'offset'  => ['name' => 'offset', 'type' => Type::int()], 
            'limit'   => ['name' => 'limit', 'type'  => Type::int()],
        ];
    }
    public function type() 
    {
        return Type::listOf(GraphQL::type('QueryLog'));  
    }

    public function resolve($root, $args)
    {
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        $limit  = isset($args['limit'])  ? $args['limit']  : 10; //获取多少条数据，默认为10

        //用户未登录的情况下,无最近搜索记录
        if( checkUser() ){
            return null;
        }

        $user = getUser();
        $querylogs = $user->querylogs()
            ->orderBy('updated_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();
            

        return $querylogs;
    }
}
