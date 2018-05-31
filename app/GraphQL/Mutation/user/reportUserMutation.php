<?php
namespace App\GraphQL\Mutation\user;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class reportUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'reportUser',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'id'         => ['name' => 'id', 'type' => Type::int()],
            'type'       => ['name' => 'type', 'type' => Type::string()],
            'reason'     => ['name' => 'reason', 'type' => Type::string()],
            'comment_id' => ['name' => 'comment_id', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'id'     => ['required'],
            'type'   => ['required'],
            'reason' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user   = User::findOrFail($args['id']);
        $reason = isset($args['reason']) ? $args['reason'] : '';
        if (isset($args['comment_id'])) {
            $user->report($args['type'], $reason, $args['comment_id']);
        } else {
            $user->report($args['type'], $reason);
        }
        return $user;
    }
}
