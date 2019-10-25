<?php

namespace App\Traits;

trait UserRepo
{

    public function createUser($name, $account, $password)
    {
        $this->account      = $account;
        $this->phone        = $account;
        $this->name         = $name;
        $this->password     = bcrypt($password);
        $avatar_formatter   = 'http://cos.' . env('APP_NAME') . '.com/storage/avatar/avatar-%d.jpg';
        $this->avatar       = sprintf($avatar_formatter, rand(1, 15));
        $this->api_token    = str_random(60);
        $this->introduction = '';
        $this->save();
        //record signUp action
        $action = \App\Action::create([
            'user_id'         => $this->id,
            'actionable_type' => 'users',
            'actionable_id'   => $this->id,
        ]);
        return $this;
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
