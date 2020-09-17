<?php
namespace App\Helpers\Redis;

use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    public static function redis($isThorw = false)
    {
        $redis = Redis::connection('cache');
        try {
            $redis->ping();
        } catch (\Predis\Connection\ConnectionException $ex) {
            $redis = null;
            //丢给sentry报告
            app('sentry')->captureException($ex);
            //继续向下抛出异常
            if ($isThorw) {
                throw new $ex;
            }
        }

        return $redis;
    }
}
