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
                    $table_name = $root->getTable();
                    //专题
                    if($table_name == 'categories'){
                        return $root->name;
                    }
                    return $root->title;
                }, 
            ],
            'logo'   => [ 
                'type'        => Type::string(),
                'description' => 'The logo of Visited',
                'resolve'     => function ($root, $args) {
                    $table_name = $root->getTable();
                    //专题
                    if($table_name == 'categories'){
                        return $root->logo_app();
                    }
                    return $root->primaryImage();
                }, 
            ],
            'description' => [
                'type'        => Type::string(),
                'description' => 'The description of Visited',
            ],
            //最近更新信息
            'dynamic_msg'        => [ 
                'type'        => Type::string(),
                'description' => '动态信息，只有category有该属性',
                'resolve'     => function ($root, $args) {
                    $table_name = $root->getTable();
                    if($table_name == 'categories'){
                        
                        $visit = \DB::table('visits')->where('user_id', getUser()->id)
                            ->where('visited_id', $root->id)
                            ->where('visited_type', 'categories')
                            ->select('updated_at')
                            ->first();
                        if( empty($visit) ){
                            return null;
                        }
                        $dynamicCount = $root->publishedArticles()->where('articles.created_at', '>',$visit->updated_at)->count(); 
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
                    }
                },
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,
        ];
    }
}
