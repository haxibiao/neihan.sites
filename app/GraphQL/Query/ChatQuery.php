<?php

namespace App\GraphQL\Query;

use App\Chat;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class ChatQuery extends Query
{
    protected $attributes = [
        'name' => 'Chat',
    ];

    public function type()
    {
        return GraphQL::type('Chat');
    }

    public function args()
    {
        return [
            'id'      => ['name' => 'id', 'type' => Type::int()],
            'with_id' => ['name' => 'with_id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $me = getUser();

        if (isset($args['id'])) {
            return Chat::findOrFail($args['id']);
        }

        if (isset($args['with_id'])) {
            $uids = [$me->id, $args['with_id']];
            sort($uids);
            $uids = json_encode($uids);
            $chat = Chat::where(['uids' => $uids])->first();
            if (!$chat) {
                $chat = Chat::firstOrNew([
                    'uids' => $uids,
                ]);
                $chat->save();
            }
            return $chat;
        }
    }
}
