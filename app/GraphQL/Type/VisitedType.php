<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class VisitedType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Visited',
        'description' => 'A Visited',
    ];

    public function fields()
    { 
        return [
            'id'         => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Visited',
            ],
            'title'   => [
                'type'        => Type::string(),
                'description' => 'The title of Visited',
                'resolve'     => function ($root, $args) {
                    dd($root->getTable());
                },
            ],
            'description' => [
                'type'        => Type::string(),
                'description' => 'The description of Visited',
            ],
            'dynamic_msg'        => [ 
                'type'        => Type::string(),
                'description' => '动态信息，只有category有该属性',
                'resolve'     => function ($root, $args) {
                    $dynamicCount = $root->visited->publishedArticles()->where('articles.created_at', '>', $root->updated_at)->count();
                    if( $dynamicCount==0 ){
                        //没有动态
                        return null;
                    } else if( $dynamicCount>99 ) {
                        //超过99条
                        return '99+';
                    } else {    
                        //未超过99条
                        return $dynamicCount;
                    }
                },
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,
        ];
    }
}
