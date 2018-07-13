<?php

namespace App\GraphQL\Query;

use App\Query as DBQuery;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class QueriesQuery extends Query
{
    protected $attributes = [
        'name'        => 'QueriesQuery',
        'description' => 'Query list',
    ];
    public function args()
    {
        return [
            'offset'  => ['name' => 'offset'    , 'type'   => Type::int()], 
            'limit'   => ['name' => 'limit'     , 'type'   => Type::int()],
        ];
    }
    public function type() 
    {
        return Type::listOf(GraphQL::type('DBQuery'));    
    }

    public function resolve($root, $args)
    {
        $offset = isset($args['offset']) ? $args['offset'] : 0;
        $limit  = isset($args['limit'])  ? $args['limit']  : 10; //获取多少条数据，默认为10
        $queries = DBQuery::where('status', '>=', 0)
            ->orderBy('hits', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();
            
        foreach ($queries as $query) {
            $query->q = str_limit($query->query, 14, ''); 
        }
        return $queries;
    }
}
