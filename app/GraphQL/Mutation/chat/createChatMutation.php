<?php
namespace App\GraphQL\Mutation\chat;

use App\Chat;
use App\User;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class createChatMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'createChatMutation',
        'description' => 'create a Chat ',
    ];

    public function type()
    {
        return GraphQL::type('Chat');
    }

    public function args()
    {
        return [
            'with_id' => [
                'name' => 'with_id', 
                'type' => Type::int()
            ],
        ];
    }

    public function rules()
    {
        return [
            'with_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $with = User::findOrFail($args['with_id']);

        $uids = [$with->id, $user->id];
        sort($uids);
        $uids = json_encode($uids);
        $chat = Chat::firstOrNew([
            'uids' => $uids,
        ]);
        $chat->save();

        $with->chats()->syncWithoutDetaching($chat->id);
        $user->chats()->syncWithoutDetaching($chat->id);

        return $chat;
    }
}
