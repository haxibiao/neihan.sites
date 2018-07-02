<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ArticleTypeEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'ArticleType',
        'description' => 'The Orders of Article query',
        'values' => [
            'VIDEO'     => 'VIDEO',     //视频
            'ARTICLE'   => 'ARTICLE',   //普通文章
        ],
    ];
}