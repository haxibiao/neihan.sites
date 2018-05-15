<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CommentOrderEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name'        => 'CommentOrder',
        'description' => 'The Orders of Comment query',
        'values'      => [
            'LIKED_MOST'   => 'LIKED_MOST',
            'LATEST_FIRST' => 'LATEST_FIRST',
            'OLD_FIRST'    => 'OLD_FIRST',
        ],
    ];
}
