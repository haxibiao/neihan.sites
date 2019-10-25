<?php
namespace App\Helpers\Pay\Alipay;

class AlipayService
{
    /**
     * 支付宝单笔转账
     *
     * @param string $outBizNo [内部业务单号]
     * @param string $account  [收款账号]
     * @param string $realName [真实姓名]
     * @param float $amount   [金额]
     * @param string $remark  [转账备注信息]
     * @return array
     */
    public static function transfer(string $outBizNo, string $account, string $realName, float $amount, $remark = "")
    {
        $alipayConfig = self::getAlipayConfig();
        $transfer     = new Transfer($alipayConfig['app_id'], $alipayConfig['private_key']);
        $transfer->setBizContent([
            'out_biz_no'      => $outBizNo,
            'payee_type'      => 'ALIPAY_LOGONID',
            'payee_account'   => $account,
            'payee_real_name' => $realName,
            'amount'          => $amount,
            'remark'          => $remark,
        ]);
        return $transfer->doPay();
    }

    /**
     * 获取支付宝配置信息
     *
     * @return array
     */
    public static function getAlipayConfig()
    {
        return config('pay.alipay');
    }
}
