<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CategoryOrderEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CategoryOrder',
        'description' => 'The Orders of Category query',
        'values' => [
            'LATEST' => 'LATEST',
            'NEW_REQUESTED' => 'NEW_REQUESTED',
            'HOT' => 'HOT',
        ],
    ];
}