<?php

namespace App\GraphQL\Query;

use App\Chat;
use App\Message;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class MessagesQuery extends Query
{
    protected $attributes = [
        'name'        => 'messages',
        'description' => 'Messages list',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Message'));
    }

    public function args()
    {
        return [
            'chat_id' => ['name' => 'chat_id', 'type' => Type::int()],
            'with_id' => ['name' => 'with_id', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'offset'  => ['name' => 'offset', 'type' => Type::int()],
        ];
    }

    public function rules()
    {
        return [
            'chat_id' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $me = getUser();

        $chat = Chat::findOrFail($args['chat_id']);
        //clear unreads
        foreach ($chat->users as $user) {
            if ($user->id == $me->id) {
                $user->pivot->unreads = 0;
                $user->pivot->save();
            }
        }

        $qb = Message::orderBy('id', 'desc');
        $qb = $qb->where('chat_id', $args['chat_id']);

        if (isset($args['offset'])) {
            $qb = $qb->skip($args['offset']);
        }
        $limit = 10;
        if (isset($args['limit'])) {
            $limit = $args['limit'];
        }
        $qb = $qb->take($limit);
        return $qb->get();
    }
}
