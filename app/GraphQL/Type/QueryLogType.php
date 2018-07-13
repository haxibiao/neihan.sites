<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class QueryLogType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'QueryLogType',
        'description' => 'A Query',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Visited',
            ],
            'query'         => [ 
                'type'        => Type::string(),
                'description' => '查询的关键字',
            ],
            'results'       => [
                'type'        => Type::string(),
                'description' => '查询的总结果数(文集，专题，文章，用户)',
            ],
            'hits'        => [
                'type'        => Type::int(),
                'description' => '该查询关键字的点击量',
            ],
            'status'        => [
                'type'        => Type::int(),
                'description' => '-1: 回收站, 0:草稿(未公开)，1:发布',
            ],
        ];
    }
}
