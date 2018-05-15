<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class MessageType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Message',
        'description' => 'A Message',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'         => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Message',
            ],
            'message'    => [
                'type'        => Type::string(),
                'description' => 'The message of Message',
            ],
            'created_at' => [
                'type'        => Type::string(),
                'description' => 'The timestamp of Message',
                'resolve'     => function ($root, $args) {
                    return $root->created_at->toDateTimeString();
                },
            ],
            'time_ago'   => \App\GraphQL\Field\TimeField::class,

            // relation
            'user'       => [
                'type'        => GraphQL::type('User'),
                'description' => 'user of message',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],
            'images'     => [
                'type'        => Type::listOf(GraphQL::type('Image')),
                'description' => 'images of message',
                'resolve'     => function ($root, $args) {
                    return $root->images;
                },
            ],
        ];
    }

}
