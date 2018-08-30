<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class VideoType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Video',
        'description' => 'A Video',
    ];

    public function fields()
    { 
        return [ 
            'id'         => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Video',
            ],
            'title'   => [
                'type'        => Type::string(),
                'description' => 'The title of Video',
            ],
            'description' => [
                'type'        => Type::string(),
                'description' => 'The description of Video',
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,
            'duration' => [
                'type'        => Type::string(),
                'description' => 'The duration of Video',
                'resolve'     => function ($root, $args) {
                    return gmdate('i:s', $root->duration);
                },
            ],
            //方便以后扩展
            'info' => [
                'type'        =>  GraphQL::type('VideoInfo'),
                'description' => '视频的属性',
                'resolve'     => function ($root, $args) {
                    $info = $root->json;
                    if(!$info){
                        return null;
                    }
                    return json_decode($info);
                },
            ],
        ];
    }
}
