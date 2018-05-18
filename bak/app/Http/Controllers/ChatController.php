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
        $withUser = User::findOrFail($user_id);
        $me       = Auth::user();

        $uids = [$user_id, Auth::id()];
        sort($uids);
        $uids = json_encode($uids);
        $chat = Chat::firstOrNew([
            'uids' => $uids,
        ]);

        $chat->save();

        return redirect()->to('/notification/#chat/' . $chat->id);
    }
}
