<?php

namespace App\Listeners\LiveRoom;

use App\LiveRoom;

class NewUserComeIn
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
     * @param \App\Events\LiveRoom\NewUserComeIn $event
     * @return void
     */
    public function handle(\App\Events\LiveRoom\NewUserComeIn $event)
    {
        $user = $event->user;
        $room = $event->liveRoom;
        // 用户进入直播间
        LiveRoom::joinLiveRoom($user, $room);
        // 记录用户进入直播间数据
        optional($room->userLive)->updateCountUsers($room);
    }
}
