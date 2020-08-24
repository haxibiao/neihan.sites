<?php

namespace App;

use App\Model;
use App\Traits\ChatAttrsCache;
use App\Traits\ChatResolvers;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Chat extends Model
{
    use ChatAttrsCache;
    use ChatResolvers;

    public $fillable = [
        'uids',
        'last_message_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(\App\Message::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\User::class)->withPivot('unreads');
    }

    //resolvers
    public function resolveCreateChat($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
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

    public function resolveUserChats($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $args['user_id'] ? \App\User::find($args['user_id']) : getUser();
        if ($user) {
            return $user->chats();
        }
        return null;
    }

    public function resolveMessages($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $chat = \App\Chat::findOrFail($args['chat_id']);
        return $chat->messages()->latest('id');
    }
}
