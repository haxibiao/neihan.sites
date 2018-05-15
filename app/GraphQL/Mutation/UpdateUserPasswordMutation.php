<?php

namespace App\GraphQL\Mutation;

use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UpdateUserPasswordMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUserPassword',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'oldpassword' => ['name' => 'oldpassword', 'type' => Type::nonNull(Type::string())],
            'password'    => ['name' => 'password', 'type' => Type::nonNull(Type::string())],
        ];
    }

    public function rules()
    {
        return [
            'oldpassword' => ['required'],
            'password'    => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        //get current authenticated user ...
        $user = session('user');

        if (!$user) {
            throw new \Exception('client not authenticated yet');
        }

        if (!password_verify($args['oldpassword'], $user->password)) {
            throw new \Exception('old password incorrect');
        }

        $user->password = bcrypt($args['password']);
        $user->save();

        return $user;
    }
}
