<?php

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Facades\GraphQL;
use Folklore\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class TransactionType extends GraphQLType
{
    protected $attributes = [
        'name'        => 'Transaction',
        'description' => 'A Transaction',
    ];

    /*
     * Uncomment tiping line to make the type input object.
     * http://graphql.org/learn/schema/#input-types
     */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id'       => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id of the transaction',
            ],
            'log'      => [
                'type'        => Type::string(),
                'description' => 'log of transaction',
            ],
            'type'     => [
                'type'        => Type::string(),
                'description' => 'type of transaction',
            ],
            'amount'   => [
                'type'        => Type::string(),
                'description' => 'amount of transaction',
            ],
            'status'   => [
                'type'        => Type::int(),
                'description' => 'status of transaction',
            ],
            'balance'  => [
                'type'        => Type::int(),
                'description' => 'balance of transaction',
            ],
            'time_ago' => \App\GraphQL\Field\TimeField::class,

            //relations

            'tip'      => [
                'type'        => GraphQL::type('Tip'),
                'description' => 'tip related to this transaction',
                'resolve'     => function ($root, $args) {
                    return $root->tip;
                },
            ],

            'fromUser'     => [
                'type'        => GraphQL::type('User'),
                'description' => 'from user of transaction',
                'resolve'     => function ($root, $args) {
                    return $root->fromUser;
                },
            ],

            'toUser'     => [
                'type'        => GraphQL::type('User'),
                'description' => 'to user of transaction',
                'resolve'     => function ($root, $args) {
                    return $root->toUser;
                },
            ],
        ];
    }
}
