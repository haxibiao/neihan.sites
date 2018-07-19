<?php
namespace App\GraphQL\Mutation\user;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class updateUserEmailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUserEmail'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'email' => ['name' => 'email', 'type' => Type::string()]
        ];
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        //validation邮箱是否重复
        $email_existed = User::where('email',$args['email'])
            ->where('id','<>',$user->id)
            ->exists();

        if( $email_existed ) {
            throw new \Exception('该邮箱已注册');
        }

        $user->email = $args['email'];
        $user->save();

        return $user;
    }
}