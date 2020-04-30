<?php

namespace App\Events\LiveRoom;

use App\LiveRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class CloseRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $liveRoom;
    public $message;
    /**
     * Create a new event instance.
     *
     * @param LiveRoom $liveRoom
     * @param string $message
     */
    public function __construct(LiveRoom $liveRoom, string $message)
    {
        $this->liveRoom = $liveRoom;
        $this->message  = $message;
    }

    public function broadcastWith(): array
    {
        return [
            'message'      => $this->message,
            'live_room_id' => $this->liveRoom->id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel
    {
        return new Channel('live_room.' . $this->liveRoom->id);
    }

    public function broadcastAs(): string
    {
        return 'close_room';
    }
}
