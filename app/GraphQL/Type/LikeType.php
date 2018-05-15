<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class LikeType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Like',
        'description' => 'A Like',
    ];

    public function fields()
    {
        return [
            'id'         => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Like',
            ],
            'liked_id'   => [
                'type'        => Type::string(),
                'description' => 'The id of liked',
            ],
            'liked_type' => [
                'type'        => Type::string(),
                'description' => 'The type of liked',
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,

            //relation

            'article'    => [
                'type'        => GraphQL::type('Article'),
                'description' => 'liked article',
                'resolve'     => function ($root, $args) {
                    if ($root->liked_type == 'articles') {
                        return $root->liked;
                    }
                },
            ],

            'comment'    => [
                'type'        => GraphQL::type('Comment'),
                'description' => 'liked comment',
                'resolve'     => function ($root, $args) {
                    if ($root->liked_type == 'comments') {
                        return $root->liked;
                    }
                },
            ],
        ];
    }
}
