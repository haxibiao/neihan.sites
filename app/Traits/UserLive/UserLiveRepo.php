<?php

namespace App\Traits\UserLive;

use App\LiveRoom;
use App\User;
use App\UserLive;

trait UserLiveRepo
{
    // 记录用户直播数据
    public static function recordLive(User $user, LiveRoom $live)
    {
        UserLive::create([
            'user_id' => $user->id,
            'live_id' => $live->id,
        ]);
    }

    // 记录直播时长
    public function recordLiveDuration(LiveRoom $room)
    {
        // created_at 不是直播时间，updated_at 是直播开始时间
        $duration            = $room->latest_live_time->diffInSeconds(now());
        $this->live_duration = $duration;
        $this->save();
    }

    // 更新直播间总观众数
    public function updateCountUsers(LiveRoom $room)
    {
        $this->count_users = $room->count_online_audience;
        $this->data        = \Redis::get($room->redis_room_key);
        $this->save();
    }

}
