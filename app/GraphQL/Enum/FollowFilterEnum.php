<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class FollowFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'FollowFilter',
        'description' => 'The filters of follows query',
        'values' => [
            'USER' => 'USER',
            'CATEGORY' => 'CATEGORY',
            'USER_CATEGORY' => 'USER_CATEGORY',
        ],
    ];
}