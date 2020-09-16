<?php

namespace App\Traits;

use App\User;
use App\Verify;
use Haxibiao\Base\Exceptions\GQLException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait VerifyRepo
{
    public function sendSMSCode($phone, $action)
    {
        $qb = User::wherePhone($phone);
        $user = $qb->first();
        // 生成验证码
        $code = rand(1000, 9999);

        $data = [
            'phone' => $user->phone,
            'code' => $code,
            'name' => $user->name ?? $user->phone,
            'action' => self::getVerificationActions()[$action],
        ];

        $verify = Verify::create([
            'user_id' => $user->id,
            'code' => $code,
            'channel' => 'sms',
            'account' => $user->phone,
            'action' => $action,
        ]);
        // 发送验证码
        if ($this->sendSMS($data) != 1) {
            throw new \App\Exceptions\GQLException('发送失败！请稍后重试');
        }
        return $verify;
    }

    public function sendLoginSMSCode( $phone, $action){
        // 生成验证码
        $code = rand(1000, 9999);
        $data = [
            'phone' => $phone,
            'code' => $code,
            'action' => self::getVerificationActions()[$action],
        ];

        $verify = Verify::create([
            'code' => $code,
            'channel' => 'sms',
            'account' =>$phone,
            'action' => $action,
        ]);

        // 发送验证码
        if ($this->sendSMS($data) != 1) {
            throw new \App\Exceptions\GQLException('发送失败！请稍后重试');
        }

        return $verify;
    }

    public static function checkSMSCode($sms_code, $phone, $action)
    {
        $qb = Verify::where([
            'account' => $phone,
            'action' => $action,
        ]);

        if ($qb->exists()) {
            $verify = $qb->orderBy('id', 'desc')->first();
            if ($verify->code != $sms_code) {
                throw new GQLException('验证失败，验证码错误');
            }

            // 验证码过期了
            if ($verify->created_at->diffInSeconds(now(), false) > self::CODE_VALID_TIME) {
                throw new GQLException('验证失败，验证码过期');
            }
            return $verify;
        } else {
            throw new GQLException('发送失败！验证码不存在');
        }
    }

}
