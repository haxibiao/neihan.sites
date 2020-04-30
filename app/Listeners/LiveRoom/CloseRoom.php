<?php

namespace App\Listeners\LiveRoom;

use App\LiveRoom;

class CloseRoom
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\LiveRoom\CloseRoom $event
     * @return void
     */
    public function handle(\App\Events\LiveRoom\CloseRoom $event)
    {
        $room = $event->liveRoom;
        // 关闭直播间需要刷新直播间状态和推流key
        $room->update([
            'push_stream_key' => null,
            'status'          => LiveRoom::STATUS_OFF,
        ]);
    }
}
