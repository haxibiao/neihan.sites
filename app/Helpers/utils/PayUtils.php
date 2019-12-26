<?php

namespace App\Helpers\Pay;

use Exception;
use Illuminate\Support\Str;
use Yansongda\Pay\Pay;

class PayUtils
{
    const PLATFORMS = [
        'alipay',
        'wechat',
    ];

    private $platform;

    private $instance;

    const WITHDRAW_SERVER_IP = '203.195.161.189';

    public function __construct($platform)
    {
        //修复老数据platform首字母是大写
        $platform = strtolower($platform);
        if (!in_array($platform, self::PLATFORMS)) {
            throw new Exception('支付方式不存在!');
        }

        $this->platform = $platform;
        $this->instance = Pay::$platform(config('pay.' . $platform));
    }

    public function transfer(string $outBizNo, string $payId, $realName, $amount, $remark = null)
    {
        $order = [];
        if ($this->platform == 'wechat') {
            //微信平台 amount 单位:/分
            $amount *= 100;
            $order = [
                'partner_trade_no' => $outBizNo,
                'openid'           => $payId,
                'check_name'       => 'NO_CHECK',
                're_user_name'     => $realName,
                'amount'           => $amount,
                'desc'             => $remark,
                'type'             => 'app',
                'spbill_create_ip' => self::WITHDRAW_SERVER_IP,
            ];
        } else if ($this->platform == 'alipay') {
            $order = [
                'out_biz_no'      => $outBizNo,
                'payee_type'      => self::isAlipayOpenId($payId) ? 'ALIPAY_USERID' : 'ALIPAY_LOGONID',
                'payee_account'   => $payId,
                'payee_real_name' => $realName,
                'amount'          => $amount,
                'remark'          => $remark,
            ];
        }

        return $this->instance->transfer($order);
    }

    public static function isAlipayOpenId($payId)
    {
        return Str::startsWith($payId, '2088') && !is_email($payId);
    }

}
