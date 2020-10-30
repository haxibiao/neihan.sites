<?php

namespace App\Http\Middleware;

use App\Exceptions\UserException;
use Closure;
use Illuminate\Support\Facades\Request;

class ThrottleGraphQL
{

    /**
     * 处理
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  int $maxAttempts
     * @param  int $decayMinutes
     * @return mixed
     */
    public function handle($request, Closure $next, $maxAttempts = 10, $decayMinutes = 1, $name)
    {
        $ip = getIp();

        if (empty($name)) {
            throw new UserException('接口标识不能为空!');
        }

        $key = sprintf('%s.%s', $ip, $name);
        // 如果访问次数超标
        if (self::checkMaxAttemptsWithIP($key, $maxAttempts)) {
            throw new UserException('请求次数过多~');
        }

        self::saveAttemptByIp($key, $decayMinutes);
        return $next($request);
    }

    /**
     * 保存ip访问记录(数)
     * @param string $decayMinutes 保存时间
     * @param int $key
     */
    public static function saveAttemptByIp(string $key, int $decayMinutes)
    {
        $attempt = cache()->get($key);
        // 第一次访问
        if (empty($attempt)) {
            cache()->put($key, 1, now()->addMinutes($decayMinutes));
        } else {
            cache()->increment($key, 1);
        }
    }

    /**
     * 检查 IP 访问次数,如果超出规定次数返回 true
     * @param string $maxAttempts 最大访问次数
     * @param int $key
     * @return bool
     */
    public static function checkMaxAttemptsWithIP(string $key, int $maxAttempts)
    {
        $attempt = cache()->get($key);
        if (!empty($attempt)) {
            return $attempt >= $maxAttempts;
        }
        return false;
    }
}
