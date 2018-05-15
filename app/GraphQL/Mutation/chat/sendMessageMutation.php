<?php
namespace App\GraphQL\Mutation\chat;

use App\Chat;
use App\User;
use App\Message;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

class sendMessageMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'sendMessageMutation',
        'description' => 'send a Message ',
    ];

    public function type()
    {
        return GraphQL::type('Message');
    }

    public function args()
    {
        return [
            'chat_id' => ['name' => 'chat_id', 'type' => Type::int()],
            'message' => ['name' => 'message', 'type' => Type::string()],
        ];
    }

    public function rules()
    {
        return [
            'chat_id' => ['required'],
            'message' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $chat    = Chat::findOrFail($args['chat_id']);
        $message = Message::create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'message' => $args['message'],
        ]);
        $chat->last_message_id = $message->id;
        $chat->save();

        $chat->withUser()->chats()->syncWithoutDetaching($chat->id);
        $user->chats()->syncWithoutDetaching($chat->id);

        //update unreads for chat with users ...
        foreach ($chat->users as $chatuser) {
            if ($chatuser->id != $user->id) {
                $chatuser->pivot->unreads = $chatuser->pivot->unreads + 1;
                $chatuser->pivot->save();
            }
        }

        return $message;
    }
}
