<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;
//自定义结构：用户存放视频的配置信息
class VideoInfoType extends BaseType
{
    protected $attributes = [
        'name' => 'VideoInfoType',
        'description' => '视频配置信息'
    ];

    public function fields()
    {
        return [
        	// 'video_urls'=> [
         //        'type'        => Type::string(),
         //        'description' => '视频的地址', 
         //    ],
            'width' => [
                'type'        => Type::int(),
                'description' => '视频的宽度',
            ],
            'height' => [
                'type'        => Type::int(),
                'description' => '视频的高度',
            ],
            'covers' => [
                'type'        => Type::listOf(Type::string()),
                'description' => '视频的所有截图',
            ],
        ]; 
    }
}
