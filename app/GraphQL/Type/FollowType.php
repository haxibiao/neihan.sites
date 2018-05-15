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
