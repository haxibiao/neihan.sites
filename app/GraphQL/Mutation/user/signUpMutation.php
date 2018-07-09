<?php
namespace App\GraphQL\Mutation\user;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class signUpMutation extends Mutation
{
    protected $attributes = [
        'name' => 'signUpMutation'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'password' => ['name' => 'password', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'name' => ['name' => 'name', 'type' => Type::string()]
        ];
    }

    public function rules()
    {
        return [
            'password' => ['required'],
            'email' => ['required', 'email'],
            'name' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $password = $args['password'];
        $email = $args['email'];
        $name = $args['name'];

        $user = User::firstOrNew([
            'email' => $email,
        ]);

        if($user->id) {
            throw new \Exception('邮箱地址已经注册');
        }

        $user->name = $name; 
        $user->password = bcrypt($password);
        $user->avatar = '/images/avatar-'.rand(1, 15).'.jpg';
        $user->api_token = str_random(60);
        $user->introduction = '';
        $user->save();

        return $user;
    }
}