<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use Haxibiao\Helpers\SMSUtils;
use App\User;
use App\Verify;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait VerifyResolvers
{
    /**
     * 发送验证码
     */
    public function sendVerifyCode($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $qb = User::wherePhone($args['phone']);

        if ($qb->exists()) {
            $user = $qb->first();
            // 生成验证码
            $code = rand(1000, 9999);

            $data = [
                'phone'  => $user->phone,
                'code'   => $code,
                'name'   => $user->name ?? $user->phone,
                'action' => self::getVerificationActions()[$args['action']],
            ];

            $verify = Verify::create([
                'user_id' => $user->id,
                'code'    => $code,
                'channel' => 'sms',
                'account' => $user->phone,
                'action'  => $args['action'],
            ]);

            // 发送验证码
            if ($this->sendSMS($data) != 1) {
                throw new GQLException('发送失败！请稍后重试');
            }

            return $verify;
        } else {
            throw new GQLException('发送失败！手机号未注册');
        }
    }

    /**
     * 效验 验证码
     */
    public function checkVerifyCode($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $qb = Verify::where([
            'account' => $args['phone'],
            'action'  => $args['action'],
        ]);

        if ($qb->exists()) {
            $verify = $qb->orderBy('id', 'desc')->first();

            if ($verify->code != $args['code']) {
                throw new GQLException('验证失败，验证码错误');
            }

            // 验证码过期了
            if ($verify->created_at->diffInSeconds(now(), false) > Verify::CODE_VALID_TIME) {
                throw new GQLException('验证失败，验证码过期');
            }

            return $verify;
        } else {
            throw new GQLException('发送失败！验证码不存在');
        }
    }

    public function retrievePassword($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $verify = $this->checkVerifyCode($root, $args, $context, $resolveInfo);

        $user = $verify->user;
        $user->update([
            'password' => bcrypt($args['newPassword']),
        ]);

        return $user;
    }

    public function sendSMS(array $data)
    {
        //手机短信通知
        SMSUtils::sendVerifyCode($data['phone'], $data['action']['sms'], $data['code']);
        return 1;
    }
}
