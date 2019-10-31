<?php

namespace App\Events;

use App\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        //不广播给当前用户
        $this->dontBroadcastToCurrentUser();
    }

    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->message->chat_id);
    }

    public function broadcastWith()
    {
        $content = $this->message->message;
        $user    = $this->message->user;

        $data = [
            'title'              => '新消息提醒',
            'user_id'            => $user->id,
            'user_avatar'        => $user->avatarUrl,
            'user_name'          => $user->name,
            'message_content'    => $content,
            'message_created_at' => time_ago($this->message->created_at),
            'message_icon'       => 'icon',
            'message_id'         => $this->message->id,
            'chat_id'            => $this->message->chat_id,
        ];

        return $data;
    }
}
