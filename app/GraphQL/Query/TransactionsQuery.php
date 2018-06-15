<?php

namespace App\GraphQL\Query;

use App\Transaction;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class TransactionsQuery extends Query
{
    protected $attributes = [
        'name' => 'Transactions',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Transaction'));
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'offset'  => ['name' => 'offset', 'type' => Type::int()],
        ];
    }

    // public function rules()
    // {
    //     return [
    //         'user_id' => ['required'],
    //     ];
    // }

    public function resolve($root, $args)
    {
        $qb = Transaction::orderBy('id', 'desc');

        if (isset($args['user_id'])) {
            $qb = $qb->where('user_id', $args['user_id']);
        }

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);
        return $qb->get();
    }
}
