<?php

namespace App\Notifications;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ChatNewMessage extends Notification
{
    use Queueable;

    public $message;
    public $user;
    public $chat;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->user    = $message->user;
        $this->chat    = $message->chat;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message_content' => $this->message->message,
            'chat_id'         => $this->chat->id,
            'user_id'         => $this->user->id,
        ];
    }
}
