<?php
namespace App\GraphQL\Mutation\user;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class signInMutation extends Mutation
{
    protected $attributes = [
        'name' => 'signInMutation',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'password' => ['name' => 'password', 'type' => Type::string()],
            'email'    => ['name' => 'email', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'password' => ['required'],
            'email'    => ['required', 'email'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::where('email', $args['email'])->first();

        if (!$user) {
            throw new \Exception('email not exist');
        }

        if (!password_verify($args['password'], $user->password)) {
            throw new \Exception('password not correct');
        }

        session()->put('user', $user);

        return $user;
    }
}
