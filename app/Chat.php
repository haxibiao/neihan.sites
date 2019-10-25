<?php

namespace App;

use App\Model;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Chat extends Model
{
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

    //resolvers TODO: move out

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
        //TODO: matomo log 用户访问聊天面板的次数等用户行为chat
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

    //attrs TODO: move out
    public function getUnreadsAttribute()
    {
        return $this->pivot->unreads;
    }

    public function getWithUserAttribute()
    {
        if ($user = getUser()) {
            $uids = json_decode($this->uids);
            $current_uid = $user->id;
            $with_id = array_sum($uids) - $current_uid;
            return User::find($with_id);
        }
        return null;
    }

    public function getLastMessageAttribute()
    {
        return $this->messages()->latest('id')->first();
    }

    public function getClearUnreadAttribute()
    {
        if ($user = checkUser()) {
            $unread_notifications = \App\Notification::where([
                'type' => 'App\Notifications\ChatNewMessage',
                'notifiable_id' => $user->id,
                'read_at' => null,
            ])->get();
            foreach ($unread_notifications as $notify) {
                $notify->read_at = now();
                $notify->save();
            }
            return true;
        }
        return false;
    }
}
