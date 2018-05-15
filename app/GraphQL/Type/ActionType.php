<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class ActionType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Action',
        'description' => 'A Action',
    ];

    public function fields()
    {
        return [
            'id'              => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the action',
            ],
            'actionable_id'   => [
                'type'        => Type::string(),
                'description' => 'The id of actionable',
            ],
            'actionable_type' => [
                'type'        => Type::string(),
                'description' => 'The type of action',
            ],
            'type' => [
                'type'        => Type::string(),
                'description' => 'The type of action',
                'resolve'     => function ($root, $args) {
                    return $root->actionable_type;
                },
            ],
            'time_ago'        => \App\GraphQL\Field\TimeField::class,

            //relation

            'signUp'          => [
                'type'        => GraphQL::type('User'),
                'description' => 'tiped object',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'users') {
                        return $root->actionable;
                    }
                },
            ],

            'tiped'           => [
                'type'        => GraphQL::type('Tip'),
                'description' => 'tiped object',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'tips') {
                        return $root->actionable;
                    }
                },
            ],

            'liked'           => [
                'type'        => GraphQL::type('Like'),
                'description' => 'liked object',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'likes') {
                        return $root->actionable;
                    }
                },
            ],

            'followed'        => [
                'type'        => GraphQL::type('Follow'),
                'description' => 'followed object',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'follows') {
                        return $root->actionable;
                    }
                },
            ],

            'postedArticle'   => [
                'type'        => GraphQL::type('Article'),
                'description' => 'posted Article',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'articles') {
                        return $root->actionable;
                    }
                },
            ],

            'postedComment'   => [
                'type'        => GraphQL::type('Comment'),
                'description' => 'posted Comment',
                'resolve'     => function ($root, $args) {
                    if ($root->actionable_type == 'comments') {
                        return $root->actionable;
                    }
                },
            ],
        ];
    }
}
