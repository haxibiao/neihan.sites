<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class QueryOrderEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'QueryOrder',
        'description' => 'The Orders of Query query',
        'values' => [
            'LATEST' => 'LATEST',      //最新时间
            'HOT' => 'HOT',            //热度
        ],
    ];
}