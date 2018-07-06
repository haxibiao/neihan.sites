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
            $user = new User();
            $user->error = '邮箱不存在';
            return $user;
        }

        if (!password_verify($args['password'], $user->password)) {
             $user->error =  '密码不正确';
             return $user;
        }

        session()->put('user', $user);

        return $user;
    }
}
