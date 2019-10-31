<?php

namespace App\Helpers;

use App\Exceptions\GQLException;
use Exception;
use Overtrue\EasySms\EasySms;

class SMSUtils
{
    /**
     * @param $mobile 手机号码
     * @param null $template 短信模板
     * @return int
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public static function sendVerifyCode($mobile, $action, $code = null)
    {
        $easySms = new EasySms(config('sms'));
        try {
            $easySms->send($mobile, [
                'template' => self::getTemplate($action),
                'data'     => [
                    'code' => $code,
                ],
            ]);
        } catch (Exception $ex) {
            throw new GQLException('发送失败，当日发送次数过多或手机号不支持，请明日再试。');
        }
        return $code;
    }

    public static function templates()
    {
        return [
            'RESET_PASSWORD'   => [
                'aliyun' => 'SMS_157655209',
                'qcloud' => '444862',
            ],
            'USER_REGISTER'    => [
                'aliyun' => 'SMS_157655210',
                'qcloud' => '444863',
            ],
            'USER_INFO_CHANGE' => [
                'aliyun' => 'SMS_157655208',
                'qcloud' => '444865',
            ],
            'USER_LOGIN'       => [
                'aliyun' => 'SMS_157655212',
                'qcloud' => '444864',
            ],
            'WECHAT_BIND'      => [
                'aliyun' => 'SMS_157655212',
                'qcloud' => '444861',
            ],
        ];
    }

    public static function getTemplate($action)
    {
        $gateways = config('sms.default.gateways');
        if (empty($gateways)) {
            throw new \App\Exceptions\UserException('短信发送失败,请联系官方人员！');
        }
        $gateways  = reset($gateways);
        $templates = self::templates();

        return $templates[$action][$gateways];

    }
}
