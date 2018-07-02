<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Chat;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function chat($id)
    {
        $chat              = Chat::findOrFail($id);
        $data['with_user'] = $chat->withUser();
        $messages          = $chat->messages()->with('user')->orderBy('id', 'desc')->paginate(10);

        foreach ($messages as $message) {
            $message->time    = $message->timeAgo();
            $message->message = str_replace("\n", "<br>", $message->message);
            $message->user->fillForJs();
        }
        $data['messages'] = $messages;

        foreach ($chat->users as $user) {
            if ($user->id == Auth::id()) {
                $user->pivot->unreads = 0;
                $user->pivot->save();
            }
        }

        return $data;
    }

    public function sendMessage($id)
    {
        $chat    = Chat::findOrFail($id);
        $message = Message::create([
            'user_id' => Auth::id(),
            'chat_id' => $chat->id,
            'message' => request()->get('message'),
        ]);

        $chat->withUser()->chats()->syncWithoutDetaching($chat->id);
        Auth::user()->chats()->syncWithoutDetaching($chat->id);

        foreach ($chat->users as $user) {
            if ($user->id != Auth::id()) {
                $user->pivot->unreads = $user->pivot->unreads + 1;
                $user->pivot->save();
                $user->forgetUnreads();
            }
            $user->fillForJs();
        }

        $message = Message::with('user')->find($message->id);
        $message->user->fillForJs();
        return $message;
    }

    public function chats(Request $request)
    {
        $user  = $request->user();
        $chats = $user->chats()->orderBy('id', 'desc')->paginate(10);
        foreach ($chats as $chat) {
            $with_user = $chat->withUser();
            if ($with_user) {
                $chat->with_id     = $with_user->id;
                $chat->with_name   = $with_user->name;
                $chat->with_avatar = $with_user->avatar();
            }

            $last_message       = $chat->messages()->orderBy('id', 'desc')->first();
            $chat->last_message = '还没开始聊天...';
            if ($last_message) {
                $chat->last_message = str_limit($last_message->message);
            }
            $chat->time    = $chat->updatedAt();
            $chat->unreads = $chat->pivot->unreads;
        }
        return $chats;
    }

    public function notifications(Request $request, $type)
    {
        $notifications = [];
        $user          = $request->user();
        foreach ($user->notifications as $notification) {
            $data = $notification->data;
            //每个通知里都有个group的 type值，方便组合通知列表
            if ($data['type'] == $type) {
                $data['time'] = $notification->created_at->toDateTimeString();

                //follow
                if ($type == 'follow') {
                    $data['is_followed'] = Auth::user()->isFollow('users', $data['user_id']);
                }
                $data['unread'] = $notification->read_at ? 0 : 1;

                //点开就标记已读...
                $notification->markAsRead();
                $user->forgetUnreads();                

                //fix avatar in local
                if (\App::environment('local')) {
                    if (!empty($data['user_avatar']) && !empty($data['user_id'])) {
                        if ($user = User::find($data['user_id'])) {
                            $data['user_avatar'] = $user->avatar();
                        }
                    }
                }

                $notifications[] = $data;
            }
        }
        dd('ok');
        return $notifications;
    }
}
