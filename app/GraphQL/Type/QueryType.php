<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class QueryType extends GraphQLType
{
    protected $attributes = [ 
        'name'        => 'Query',
        'description' => 'A Query',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Visited',
            ],
            'user_id'         => [ 
                'type'        => Type::int(),
                'description' => '触发该查询的用户Id,为空则是游客',
            ],
            'query'         => [ 
                'type'        => Type::string(),
                'description' => '查询的关键字', 
            ],
        ];
    }
      
}
