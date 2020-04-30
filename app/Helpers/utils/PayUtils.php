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
        $config = config('pay.' . $platform);
        //微信提现配置替换appid
        if ($platform == 'wechat') {
            $config['appid'] = config('wechat.wechat_app.appid');
        }

        $this->platform = $platform;
        $this->instance = Pay::$platform($config);
    }

    public function transfer(string $outBizNo, string $payId, string $realName, $amount, $remark = null)
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
                'spbill_create_ip' => '203.195.161.189',
            ];
        } else if ($this->platform == 'alipay') {
            $order = [
                'out_biz_no'   => $outBizNo,
                'biz_scene'    => 'DIRECT_TRANSFER',
                'trans_amount' => $amount,
                'product_code' => 'TRANS_ACCOUNT_NO_PWD',
                'payee_info'   => [
                    'identity'      => $payId,
                    'identity_type' => self::isAlipayOpenId($payId) ? 'ALIPAY_USER_ID' : 'ALIPAY_LOGON_ID',
                    'name'          => $realName,
                ],
                'remark'       => $remark,
                'order_title'  => $remark,
            ];
        }

        return $this->instance->transfer($order);
    }

    public static function isAlipayOpenId($payId)
    {
        return Str::startsWith($payId, '2088') && !is_email($payId);
    }

}
