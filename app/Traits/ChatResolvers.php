<?php

namespace App\Traits;

use App\Chat;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait ChatResolvers
{
    public function resolveCreateChat($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user('发送消息');

        $user = getUser();
        $with = User::findOrFail($args['with_user_id']);
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
