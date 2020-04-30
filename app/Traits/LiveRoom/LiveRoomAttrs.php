<?php

namespace App\Traits\LiveRoom;

use Illuminate\Support\Facades\Redis;

trait LiveRoomAttrs
{
    public function getRedisRoomKeyAttribute(): string
    {
        return "live_room_{$this->id}";
    }

    public function getCountOnlineAudienceAttribute(): int
    {
        $count = json_decode(Redis::get($this->redis_room_key), true);
        return $count ? count($count) : 0;
    }

    public function getPushUrlAttribute(): string
    {
        return self::prefix . $this->push_stream_url . $this->push_stream_key;
    }

    public function getPullUrlAttribute(): string
    {
        return self::prefix . config('tencent-live.live_pull_url') . $this->stream_name;
    }

    public function getCoverUrlAttribute(): string
    {
        if (!$this->cover) {
            return 'https://dtzq-1251052432.cos.ap-shanghai.myqcloud.com/2020-03-25/u%3A980235-screenshot-15-20-45-1192x746.jpg';
        }
        return \Storage::cloud()->url($this->cover);
    }
}
