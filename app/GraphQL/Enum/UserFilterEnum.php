<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class UserFilterEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name'        => 'UserFilter',
        'description' => 'The filters of User query',
        'values'      => [
            'RECOMMEND'      => 'RECOMMEND',

            //by user follow
            'FOLLOWINGS'     => 'FOLLOWINGS',
            'FOLLOWERS'      => 'FOLLOWERS',

            //by category
            'CATE_ADMINS'    => 'CATE_ADMINS',
            'CATE_AUTHORS'   => 'CATE_AUTHORS',
            'CATE_FOLLOWERS' => 'CATE_FOLLOWERS',

        ],
    ];
}
