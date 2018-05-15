<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CategoryFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CategoryFilter',
        'description' => 'The filters of category query',
        'values' => [
            'SPECIAL' => 'SPECIAL',
            'RECOMMEND' => 'RECOMMEND',

            //my
            'MINE' => 'MINE',
            'ADMIN' => 'ADMIN',
            'FOLLOWED' => 'FOLLOWED',
            'REQUESTED' => 'REQUESTED',
        ],
    ];
}