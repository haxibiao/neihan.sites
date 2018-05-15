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

        $user->name = $args['name'];
        $user->save();

        return $user;
    }
}