<?php

namespace App\Events\LiveRoom;

use App\LiveRoom;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NewUserComeIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $user;
    public $liveRoom;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param LiveRoom $liveRoom
     */
    public function __construct(User $user, LiveRoom $liveRoom)
    {
        $this->user     = $user;
        $this->liveRoom = $liveRoom;
    }

    public function broadcastWith(): array
    {

        return [
            'user_id'        => $this->user->id,
            'user_name'      => $this->user->name,
            'user_avatar'    => $this->user->avatar_url,
            'message'        => "{$this->user->name} 进入了直播房间",
            'count_audience' => $this->liveRoom->count_online_audience,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('live_room.' . $this->liveRoom->id);
    }

    public function broadcastAs(): string
    {
        return 'user_come_in';
    }
}
