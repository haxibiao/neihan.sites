<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat($user_id)
    {
        $user_id = intval($user_id);
    	$withUser = User::findOrFail($user_id);
    	$me = Auth::user();

        $uids = [$user_id, Auth::id()];
        sort($uids);
        $uids = json_encode($uids);
        $chat = Chat::firstOrNew([
            'uids' => $uids,
        ]);
        $chat->save();

        // $withUser->chats()->syncWithoutDetaching($chat->id);
        // $me->chats()->syncWithoutDetaching($chat->id);

        return redirect()->to('/notification/#chat/' . $chat->id);
    }
}
