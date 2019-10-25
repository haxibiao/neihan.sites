<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Notifications\ChatNewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewMessageNotification implements ShouldQueue
{

    public function __construct()
    {
        //
    }

    public function handle(NewMessage $event)
    {
        $message = $event->message;
        $chat    = $event->message->chat;
        foreach ($chat->users as $user) {
            $user->notify(new ChatNewMessage($message));
        }
    }
}
