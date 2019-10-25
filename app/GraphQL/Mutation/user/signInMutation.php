<?php
namespace App\GraphQL\Mutation\user;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use \App\Exceptions\ValidationExcetion;

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

        // if (!$user) {
        //     throw new \Exception('邮箱不存在');
        // }

        if (empty($user) || !password_verify($args['password'], $user->password) ) {
            throw new ValidationExcetion('邮箱或密码不正确'); 
        }

        session()->put('user', $user);

        return $user;
    }
}
