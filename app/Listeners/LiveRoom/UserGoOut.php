<?php

namespace App\Listeners\LiveRoom;

use Illuminate\Support\Facades\Redis;

class UserGoOut
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
     * @param \App\Events\LiveRoom\UserGoOut $event
     * @return void
     */
    public function handle(\App\Events\LiveRoom\UserGoOut $event)
    {
        $room = $event->liveRoom;
        $user = $event->user;

        $users = Redis::get($room->redis_room_key);
        if ($users) {
            $userIds = json_decode($users, true);
            // 从数组中删除要离开的用户
            $userIds = array_diff($userIds, array($user->id));
            Redis::set($room->redis_room_key, json_encode($userIds));
        }
    }
}
