<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class FollowType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Follow',
        'description' => 'A Follow',
    ];

    public function fields()
    {
        return [
            'id'            => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the follow',
            ],
            'followed_id'   => [
                'type'        => Type::string(),
                'description' => 'The id of followed',
            ],
            'followed_type' => [
                'type'        => Type::string(),
                'description' => 'The type of follow',
            ],
            'time_ago'      => \App\GraphQL\Field\TimeField::class,

            // direct access attrs
            'name'          => [
                'type'        => Type::string(),
                'description' => 'The name of this followed object',
                'resolve'     => function ($root, $args) {
                    return $root->followed->name;
                },
            ],
            'avatar'        => [
                'type'        => Type::string(),
                'description' => 'The avatar/logo of this follow',
                'resolve'     => function ($root, $args) {
                    if ($root->followed_type == 'users') {
                        return $root->followed->avatar;
                    } else {
                        return $root->followed->logo;
                    }
                }, 
            ],
            'latest_article_title'        => [ 
                'type'        => Type::string(),
                'description' => 'latest create article title',
                'resolve'     => function ($root, $args) {
                    $latest_article = $root->followed
                        ->publishedArticles()
                        ->latest()->first();     
                    if( empty($latest_article) ){
                        return null;
                    }
                    return $latest_article->title;
                },
            ],
            'dynamic_msg'        => [ 
                'type'        => Type::string(),
                'description' => 'dynamic messsage',
                'resolve'     => function ($root, $args) {
                    $dynamicCount = $root->followed->publishedArticles()->where('articles.created_at', '>', $root->updated_at)->count();
                    if( $dynamicCount==0 ){
                        //没有动态
                        return null;
                    } else if( $dynamicCount>99 ) {
                        //超过99条
                        return '99+篇文章';
                    } else {    
                        //超过99条动态信息
                        return $dynamicCount . '篇文章';
                    }
                },
            ],

            //relation
            'user'          => [
                'type'        => GraphQL::type('User'),
                'description' => 'followed User',
                'resolve'     => function ($root, $args) {
                    if ($root->followed_type == 'users') {
                        return $root->followed;
                    }
                },
            ],

            'category'      => [
                'type'        => GraphQL::type('Category'),
                'description' => 'followed category',
                'resolve'     => function ($root, $args) {
                    if ($root->followed_type == 'categories') {
                        return $root->followed;
                    }
                },
            ],

            'collection'    => [
                'type'        => GraphQL::type('Collection'),
                'description' => 'followed collection',
                'resolve'     => function ($root, $args) {
                    if ($root->followed_type == 'collections') {
                        return $root->followed;
                    }
                },
            ],
        ];
    }
}
