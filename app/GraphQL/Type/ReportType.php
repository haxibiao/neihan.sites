<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ReportType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Report',
        'description' => 'A Report',
    ];

    /*
     * UnImage following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'user'    => [
                'type'        => GraphQL::type('User'),
                'description' => 'user who reported',
                'resolve'     => function ($root, $args) {
                    $user_id = array_keys($root)[0];
                    $user    = \App\User::find($user_id);
                    if ($user) {
                        return $user;
                    }
                },
            ],

            'type'    => [
                'type'        => Type::string(),
                'description' => 'type of report',
                'resolve'     => function ($root, $args) {
                    $values = array_values($root)[0];
                    if (!empty($values['type'])) {
                        return $values['type'];
                    }
                },
            ],

            'reason'  => [
                'type'        => Type::string(),
                'description' => 'reason of report',
                'resolve'     => function ($root, $args) {
                    $values = array_values($root)[0];
                    if (!empty($values['reason'])) {
                        return $values['reason'];
                    }
                },
            ],

            'comment' => [
                'type'        => GraphQL::type('Comment'),
                'description' => '举报这个用户的评论',
                'resolve'     => function ($root, $args) {
                    $values = array_values($root)[0];
                    if (!empty($values['comment_id'])) {
                        return \App\Comment::find($values['comment_id']);
                    }
                },
            ],
        ];
    }

}
