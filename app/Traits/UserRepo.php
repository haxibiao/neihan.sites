<?php

namespace App\Traits;

use App\Profile;
use App\User;

trait UserRepo
{

    public function createUser($name, $account, $password)
    {
        $user           = new User();
        $user->account  = $account;
        $user->phone    = $account;
        $user->name     = $name;
        $user->password = bcrypt($password);

        $user->avatar    = User::AVATAR_DEFAULT;
        $user->api_token = str_random(60);

        $user->save();

        Profile::create([
            'user_id' => $user->id,
        ]);

        return $user;
    }

    public function canJoinChat($chatId)
    {
        $chats = $this->chats()->where('chat_id', $chatId)->first();
        if (!is_null($chats)) {
            return true;
        }
        return false;
    }
}
