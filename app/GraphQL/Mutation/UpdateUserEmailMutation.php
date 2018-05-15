<?php
namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class UpdateUserEmailMutation extends Mutation
{
    protected $attributes = [
        'name' => 'UpdateUserEmail'
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
        //get current authenticated user ...
        $user = session('user');

        if (!$user) {
            throw new \Exception('client not authenticated yet');
        }

        $user->email = $args['email'];
        $user->save();

        return $user;
    }
}