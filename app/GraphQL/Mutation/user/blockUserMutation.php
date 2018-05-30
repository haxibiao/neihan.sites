<?php
namespace App\GraphQL\Mutation\user;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class blockUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'blockUser',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'user_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();
        $user->blockUser($args['user_id']);
        return $user;
    }
}
