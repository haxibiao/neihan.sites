<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CommentFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CommentFilter',
        'description' => 'The filters of Comment query',
        'values' => [
            'ONLY_AUTHOR' => 'ONLY_AUTHOR',
            'ALL' => 'ALL',
        ],
    ];
}