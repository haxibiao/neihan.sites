<?php
namespace App\Helpers\Redis;


class RedisSharedCounter
{
    public static function updateCounter($userId, $count = 1)
    {
        $isUpdated = false;
        $redis     = RedisHelper::redis();
        $cacheKey  = RedisSharedCounter::cacheKey();
        if ($redis && !empty($cacheKey) && !empty($userId)) {
            $redis->hincrby($cacheKey, $userId, $count);
            //当天最后23:59:59秒缓存失效
            if ($redis->ttl($cacheKey) == -1) {
                $redis->expireat($cacheKey, now()->endOfDay()->timestamp);
            }
        }

        return $isUpdated;
    }

    public static function getCounter($userId)
    {
        $count    = 0;
        $redis    = RedisHelper::redis();
        $cacheKey = RedisSharedCounter::cacheKey();
        if ($redis && !empty($cacheKey)) {
            $count = $redis->hget($cacheKey, $userId);
        }

        return $count;
    }

    public static function cacheKey()
    {
        return 'user:shared:counter:' . date('Ymd');
    }
}
