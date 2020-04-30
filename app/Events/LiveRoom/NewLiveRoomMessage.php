<?php

namespace App\Events\LiveRoom;

use App\LiveRoom;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Str;

class NewLiveRoomMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $user;
    public $message;
    public $liveRoom;

    /**
     * Create a new event instance.
     *
     * @param $userId 观众id
     * @param $liveRoomId 直播室id
     * @param $message 弹幕内容
     */
    public function __construct($userId, $liveRoomId, $message)
    {
        $this->user     = User::find($userId);
        $this->liveRoom = LiveRoom::find($liveRoomId);
        $this->message  = $message;
    }

    public function broadcastWith(): array
    {
        $popup = false;
        // 给大哥大姐们的彩蛋
        if (Str::contains($this->message, ['杨柳', '李峥', '胡蹦', '小谷', '罗静', '小芳', '张总', '老王', '王彬'])) {
            $popup = true;
        }
        return [
            'user_id'      => $this->user->id,
            'user_name'    => $this->user->name,
            'user_avatar'  => $this->user->avatar_url,
            'live_room_id' => $this->liveRoom->id,
            'message'      => $this->message,
            // 彩蛋
            'egg'          => [
                'popup' => $popup,
                'type'  => 'BboBbo',
            ],
        ];
    }

    public function broadcastOn(): Channel
    {
        return new Channel('live_room.' . $this->liveRoom->id);
    }

    public function broadcastAs(): string
    {
        return 'new_comment';
    }
}
