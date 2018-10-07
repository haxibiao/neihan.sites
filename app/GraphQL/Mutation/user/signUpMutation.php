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
        
        //用户名或邮箱都不能重复，这里没有放到rules中的原因是：检验的错误信息json结构不兼容前端已经绑定的结构。后面有时间可以考虑与前端重构一下
        $user_existed = User::where('email',$email)
            ->orWhere('name',$name)
            ->exists();

        if( $user_existed ) {
            throw new \Exception('该用户名或邮箱已经存在');
        }
        $user = new User();
        return $user->createUser($args);
    }
}