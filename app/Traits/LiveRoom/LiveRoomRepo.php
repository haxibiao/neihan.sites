<?php

namespace App\Traits\LiveRoom;

use App\Events\LiveRoom\CloseRoom;
use App\LiveRoom;
use App\User;
use Illuminate\Support\Facades\Redis;

trait LiveRoomRepo
{

    /**
     * 获取腾讯云推流密钥(主播使用)
     * @param $domain
     * @param $streamName
     * @param $key
     * @param null $endTime
     * @return string
     */
    public static function getPushUrl($domain, $streamName, $key, $endTime = null): string
    {
        if ($key && $endTime) {
            $txTime   = strtoupper(base_convert(strtotime($endTime), 10, 16));
            $txSecret = md5($key . $streamName . $txTime);
            $ext_str  = '?' . http_build_query(array(
                'txSecret' => $txSecret,
                'txTime'   => $txTime,
            ));
        }
        return $streamName . ($ext_str ?? '');
    }

    // 生成鉴权密钥
    public static function getUserPushLiveUrl($streamName): string
    {
        //直播结束时间
        $endAt  = now()->addDay()->toDateTimeString();
        $key    = config('tencent-live.live_key');
        $domain = config('tencent-live.live_push_url');
        //流名称,用于鉴别不同的主播,必须唯一
        return self::getPushUrl($domain, $streamName, $key, $endAt);
    }

    /**
     * 主播创建直播室,初始化流信息
     * @param User $user 主播用户
     * @param string $title 直播间标题
     * @return LiveRoom 直播室对象
     */
    public static function createLiveRoom(User $user, string $title): LiveRoom
    {
        list($streamName, $key, $domain, $pullUrl) = self::getLiveConfig($user);

        $pullStreamUrl = $pullUrl . $streamName;
        $room          = LiveRoom::create([
            'anchor_id'        => $user->id,
            'push_stream_url'  => $domain,
            'push_stream_key'  => $key,
            'pull_stream_url'  => $pullStreamUrl,
            'stream_name'      => $streamName,
            'latest_live_time' => now(),
            'status'           => LiveRoom::STATUS_ON,
            'title'            => $title,
        ]);

        // 设置redis 直播室初始值
        Redis::set($room->redis_room_key, json_encode(array($user->id)));
        // 一天后过期
        Redis::expire($room->redis_room_key, now()->addDay()->diffInSeconds(now()));

        return $room;
    }

    /**
     * 加入直播间
     * @param User $user
     * @param LiveRoom $room
     * @return null
     */
    public static function joinLiveRoom(User $user, LiveRoom $room)
    {
        if (Redis::exists($room->redis_room_key)) {

            if (empty(Redis::get($room->redis_room_key))) {
                $appendValue = array($user->id);
            } else {
                $users       = json_decode(Redis::get($room->redis_room_key), true);
                $users[]     = $user->id;
                $appendValue = $users;
            }
            // 去重
            $appendValue = array_unique($appendValue);
            Redis::set($room->redis_room_key, json_encode($appendValue));

        }
        return null;
    }

    /**
     * 已经有直播间,继续直播
     * @param User $user
     * @param LiveRoom $liveRoom
     * @param string $title
     * @return LiveRoom
     */
    public static function openLive(User $user, LiveRoom $liveRoom, string $title): LiveRoom
    {
        list($streamName, $key, $domain, $pullUrl) = self::getLiveConfig($user);
        $pullStreamUrl                             = $pullUrl . $streamName;
        $liveRoom->update([
            'latest_live_time' => now(),
            'title'            => $title,
            'push_stream_key'  => $key,
            'push_stream_url'  => $domain,
            'pull_stream_url'  => $pullStreamUrl,
            'stream_name'      => $streamName,
            'status'           => self::STATUS_ON,
        ]);

        Redis::del($liveRoom->redis_room_key);
        Redis::set($liveRoom->redis_room_key, json_encode(array($user->id)));
        // 一天后过期
        Redis::expire($liveRoom->redis_room_key, now()->addDay()->diffInSeconds(now()));
        return $liveRoom;
    }

    /**
     * 关闭直播间
     * @param LiveRoom $room
     */
    public static function closeRoom(LiveRoom $room)
    {
        event(new CloseRoom($room, '主播关闭了直播~'));
        if (Redis::exists($room->redis_room_key)) {
            Redis::del($room->redis_room_key);
        }
    }

    /**
     * 获取流名称（唯一）
     * @param User $user
     * @return string
     */
    public static function getStreamName(User $user): string
    {
        return "u:{$user->id}";
    }

    /**
     * @param User $user
     * @return array
     */
    public static function getLiveConfig(User $user): array
    {
        $streamName = self::getStreamName($user);
        $key        = self::getUserPushLiveUrl($streamName);
        $domain     = config('tencent-live.live_push_url');
        $pullUrl    = config('tencent-live.live_pull_url');
        return array($streamName, $key, $domain, $pullUrl);
    }
}
