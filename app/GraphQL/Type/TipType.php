<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class TipType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'tip',
        'description' => 'A tip',
    ];

    /*
     * Uncomment tiping line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'           => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'The id of the tip',
            ],
            'tipable_id'   => [
                'type'        => Type::string(),
                'description' => 'The id of tiped',
            ],
            'tipable_type' => [
                'type'        => Type::string(),
                'description' => 'The type of tip',
            ],
            'amount'       => [
                'type'        => Type::string(),
                'description' => 'amount of this tip',
            ],
            'message'      => [
                'type'        => Type::string(),
                'description' => 'message of this tip',
            ],
            'time_ago'     => \App\GraphQL\Field\TimeField::class,

            //relations
            
            'article'      => [
                'type'        => GraphQL::type('Article'),
                'description' => 'tiped article',
                'resolve'     => function ($root, $args) {
                    return $root->tipable;
                },
            ],

            'user'         => [
                'type'        => GraphQL::type('User'),
                'description' => 'user who made the tip',
                'resolve'     => function ($root, $args) {
                    return $root->user;
                },
            ],
        ];
    }
}
