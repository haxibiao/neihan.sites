<?php
namespace App\GraphQL\Mutation\user;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class userTransferMutation extends Mutation
{
    protected $attributes = [
        'name' => 'userTransfer',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'amount'     => ['name' => 'amount', 'type' => Type::int()],
            'to_user_id' => ['name' => 'to_user_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'amount'     => ['required'],
            'to_user_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user    = getUser();
        $to_user = \App\User::findOrFail($args['to_user_id']);
        $user->transfer($args['amount'], $to_user);
        return $user;
    }
}
