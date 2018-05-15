<?php
namespace App\GraphQL\Mutation\user;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class updateUserIntroductionMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUserIntroduction'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'introduction' => ['name' => 'introduction', 'type' => Type::string()]
        ];
    }

    public function rules()
    {
        return [
            'introduction' => ['required']
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $user->introduction = $args['introduction'];
        $user->save();

        return $user;
    }
}