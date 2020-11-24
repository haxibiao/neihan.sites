<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWithdrawNow;
use App\User;
use App\Withdraw;
use Haxibiao\Helpers\utils\PayUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WithdrawController extends Controller
{

    //提现
    public function withdraws()
    {
        $amount = request()->get('amount');
        $platform = request()->get('platform') ?? 'alipay';
        $toAccount = request()->get('toAccount');
        $realName = request()->get('realName');

        $user = getUser();
        if ($user) {
            $wallet = $user->wallet;
            $withdraw = new Withdraw();
            $withdraw = $withdraw->store($wallet, $amount, $platform, $toAccount);
            dispatch_now(new ProcessWithdrawNow($withdraw->id, $realName));
        }
    }

    public function transferPayPlatform($outBizNo, $payId, $realName, $amount, $remark, $platform)
    {
        $result = [];
        //转账
        $payUtils = new PayUtils($platform);
        try {
            $transferResult = $payUtils->transfer($outBizNo, $payId, $realName, $amount, $remark);
        } catch (\Exception $ex) {
            $transferResult = $ex->raw ?? null;
        }

        //处理支付响应
        if ($platform == Withdraw::WECHAT_PLATFORM) {
            //微信余额不足
            if (Arr::get($transferResult, 'err_code') != 'NOTENOUGH') {
                $result['order_id']   = $transferResult['payment_no'] ?? null;
                $result['failed_msg'] = $transferResult['err_code_des'] ?? null;
            }
        } else if ($platform == Withdraw::ALIPAY_PLATFORM) {
            //支付宝余额不足、转账失败
            if (isset($transferResult['alipay_fund_trans_uni_transfer_response'])) {
                $transferResult = $transferResult['alipay_fund_trans_uni_transfer_response'];
            }

            if (Arr::get($transferResult, 'sub_code') != 'PAYER_BALANCE_NOT_ENOUGH') {
                $result['order_id']   = $transferResult['order_id'] ?? null;
                $result['failed_msg'] = $transferResult['sub_msg'] ?? null;
            }
        }
        return $result;
    }
}
