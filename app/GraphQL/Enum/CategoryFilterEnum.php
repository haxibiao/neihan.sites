<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class CategoryFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'CategoryFilter',
        'description' => 'The filters of category query',
        'values' => [
            'SPECIAL'           => 'SPECIAL',
            'RECOMMEND'         => 'RECOMMEND',

            //my
            'MINE' => 'MINE',                       //我创建的专题
            'ADMIN' => 'ADMIN',                     //管理的专题
            'FOLLOWED' => 'FOLLOWED',               //关注的专题
            'REQUESTED' => 'REQUESTED',             //有投稿请求的专题
            'LATEST_REQUEST'   => 'LATEST_REQUEST', //最近投稿的专题
        ],
    ];
}