<?php
namespace App\GraphQL\Mutation\user;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class updateUserNameMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUserName'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::string()]
        ];
    }

    public function rules()
    {
        return [
            'name' => ['required']
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();
        
        //validation邮箱是否重复
        $name_existed = User::where('name',$args['name'])
            ->where('id','<>',$user->id)
            ->exists();

        if( $name_existed ) {
            throw new \Exception('该用户名已经存在');
        }

        $user->name = $args['name'];
        $user->save();

        return $user;
    }
}