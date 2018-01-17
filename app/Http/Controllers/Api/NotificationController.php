<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function chat($id)
    {
        $chat              = Chat::findOrFail($id);
        $data['with_user'] = $chat->withUser();
        $messages          = $chat->messages()->with('user')->orderBy('id', 'desc')->paginate(10);
        // $data['last_page'] = $messages->last_page;

        // if (request('page') == 1) {
        //     $messages = $messages->sortBy('asc');
        // }

        foreach ($messages as $message) {
            $message->time = $message->timeAgo();
        }
        $data['messages'] = $messages;

        foreach ($chat->users as $user) {
            if ($user->id == Auth::id()) {
                $user->pivot->unreads = 0;
                $user->pivot->save();
            }
        }
        Auth::user()->forgetUnreads();

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
        }

        $message = Message::with('user')->find($message->id);

        return $message;
    }

    public function chats(Request $request)
    {
        $user  = $request->user();
        $chats = $user->chats()->orderBy('id', 'desc')->paginate(10);
        foreach ($chats as $chat) {
            $with_user         = $chat->withUser();
            $chat->with_id     = $with_user->id;
            $chat->with_name   = $with_user->name;
            $chat->with_avatar = $with_user->avatar;

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
            if ($data['type'] == $type) {
                $data['time'] = $notification->created_at->toDateTimeString();

                if ($type == 'follow') {
                    $data['is_followed'] = Auth::user()->isFollow('users', $data['user_id']);
                }
                $data['unread'] = $notification->read_at ? 1 : 0;

                if ($type != 'category_request') {
                    $notification->markAsRead();
                    $user->forgetUnreads();
                }

                if ($type == 'category_request') {
                    $notification->markAsRead();
                    $user->forgetUnreads();
                    $notification->submited_status = '待审核';
                    if ($request->get('category_id')) {
                        if ($data['category_id'] != $request->get('category_id')) {
                            continue;
                        } else {
                            if (!$notification->read_at) {
                                $pviot = DB::table('article_category')->where('category_id', $data['category_id'])->where('article_id', $data['article_id'])->first();
                                if ($pviot) {
                                    $data['submited_status'] = $pviot->submit;
                                }
                            }
                        }
                    } else {
                        if ($notification->read_at) {
                            continue;
                        }
                    }
                }

                $notifications[] = $data;
            }
        }
        $user->forgetUnreads();
        return $notifications;
    }

    public function newReuqestCategories(Request $request)
    {
        $user = $request->user();
        return $user->newReuqestCategories;
    }
}
