<?php
namespace App\GraphQL\Enum;

use Folklore\GraphQL\Support\Type as GraphQLType;

class ArticleFilterEnum extends GraphQLType {
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'ArticleFilter',
        'description' => 'The filters of Article query',
        'values' => [
            'TOP' => 'TOP',
            'RECOMMEND' => 'RECOMMEND',

            //filter with category submit request status showing ...
            'CATE_SUBMIT_STATUS' => 'CATE_SUBMIT_STATUS',

            //filter new requested articles
            'NEW_REQUESTED' => 'NEW_REQUESTED',
            'ALL' => 'ALL',
            'PEDING' => 'PENDING',//待审核投稿请求

            // //filter times;
            // 'HOT_DAY'=>'HOT_DAY',
            // 'HOT_WEEK'=>'HOT_WEEK',
            // 'HOT_MOUNTH'=>'MOUNTH',

            //my
            'DRAFTS' => 'DRAFTS',
            'LIKED' => 'LIKED',
            'FAVED' => 'FAVED',
            'TRASH' => 'TRASH',
            //排序规则
            'TIME' => 'TIME',//按时间排序
            'HOT'  => 'HOT', //文章热度
        ],
    ];
}