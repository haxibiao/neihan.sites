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
            if ($data['type'] == $type) {
                $data['time'] = $notification->created_at->toDateTimeString();

                if ($type == 'follow') {
                    $data['is_followed'] = Auth::user()->isFollow('users', $data['user_id']);
                }
                $data['unread'] = $notification->read_at ? 0 : 1;

                //只要投稿请求，都点开就标记已读...
                if ($type != 'category_request') {
                    $notification->markAsRead();
                    $user->forgetUnreads();

                }

                if ($type == 'category_request') {
                    //查看具体某个分类的新请求

                    //TODO::这里有时会产生一个bug,请求的id和notification data中的id会不一致
                    //点开的同时应该清理专题未读的新投稿请求
                    $category = Category::find(request('category_id'));
                    if (!empty($category)) {
                        $category->new_requests = 0;
                        $category->save(['timestamp' => false]);
                    }

                    if (request('category_id')) {
                        if ($data['category_id'] != request('category_id')) {
                            continue;
                        }
                    } else {
                        //只显示未处理的投稿请求(unread notification only)
                        if ($notification->read_at) {
                            continue;
                        }
                    }

                    //提取投稿状态
                    $category = Category::find($data['category_id']);
                    if ($category) {
                        $article = $category->articles()->where('articles.id', $data['article_id'])->first();
                        if ($article) {
                            // 不是待审核, 都点开就标记已读
                            if ($article->pivot->submit != '待审核') {
                                $notification->markAsRead();
                                $user->forgetUnreads();
                            }
                            $data['submited_status'] = $article->pivot->submit;
                        }
                    }
                }

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
        return $notifications;
    }

    public function newReuqestCategories(Request $request)
    {
        $user = $request->user();
        return $user->newReuqestCategories;
    }
}
