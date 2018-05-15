<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ChatType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Chat',
        'description' => 'A Chat',
    ];

    /*
     * Uncomment following line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'          => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the Chat',
            ],
            'time_ago'    => \App\GraphQL\Field\TimeField::class,
            'updated_at'  => \App\GraphQL\Field\UpdatedAtField::class,

            //relations
            'unreads'     => [
                'type'    => Type::int(),
                'resolve' => function ($root, $args) {
                    return $root->pivot->unreads;
                },
            ],
            'lastMessage' => [
                'type'        => GraphQL::type('Message'),
                'description' => 'The last message of Chat',
                'resolve'     => function ($root, $args) {
                    return $root->messages()->orderBy('id', 'desc')->first();
                },
            ],
            'withUser'    => [
                'type'        => GraphQL::type('User'),
                'description' => 'The with user of Chat',
                'resolve'     => function ($root, $args) {
                    return $root->withUser();
                },
            ],
            'messages'    => [
                'type'        => Type::listOf(GraphQL::type('Message')),
                'description' => 'messages of chat',
                'args'        => [
                    'offset' => ['name' => 'offset', 'type' => Type::int()],
                    'limit'  => ['name' => 'limit', 'type' => Type::int()],
                ],
                'resolve'     => function ($root, $args) {
                    $qb = $root->messages();

                    if (isset($args['offset'])) {
                        $qb = $qb->skip($args['offset']);
                    }
                    $limit = 10;
                    if (isset($args['limit'])) {
                        $limit = $args['limit'];
                    }
                    $qb = $qb->take($limit);
                    return $qb->get();
                },
            ],
        ];
    }

}
