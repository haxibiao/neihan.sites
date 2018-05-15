<?php
namespace App\GraphQL\Mutation\follow;

use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class followUserMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'followUserMutation',
        'description' => 'follow a User ',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'user_id' => ['name' => 'user_id', 'type' => Type::int()],
            'undo'          => ['name' => 'undo', 'type' => Type::boolean()],
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
        $me = getUser();

        $user = User::findOrFail($args['user_id']);
        if ((isset($args['undo']) && $args['undo']) || session('followed_user_' . $args['user_id'])) {
            session()->put('followed_user_' . $args['user_id'], 0);

            // delete follow User
            $follow = \App\Follow::where([
                'user_id'       => $me->id,
                'followed_id'   => $args['user_id'],
                'followed_type' => 'users',
            ])->first();
            if ($follow) {
                $follow->delete();
            }
        } else {
            session()->put('followed_user_' . $args['user_id'], 1);

            // save follow User
            $follow = \App\Follow::firstOrNew([
                'user_id'       => $me->id,
                'followed_id'   => $args['user_id'],
                'followed_type' => 'users',
            ]);
            $follow->save();

            // record action
            $action = \App\Action::create([
                'user_id'         => $me->id,
                'actionable_type' => 'follows',
                'actionable_id'   => $follow->id,
            ]);
        }
        $user->count_follows = $user->follows()->count();
        $user->save();
        return $user;
    }
}
