<?php

namespace App\Traits;

use Haxibiao\Helpers\PayUtils;
use App\Withdraw;

trait WalletAttrsCache
{

    public function getBalanceCache()
    {
        $lastTransaction = $this->transactions()->latest('id')->select('balance')->first();
        return $lastTransaction->balance ?? 0;
    }

    public function getAvailableBalanceCache()
    {
        $availableBalance = $this->balance - $this->withdraws()
            ->where('status', Withdraw::WAITING_WITHDRAW)
            ->sum('amount');

        return $availableBalance;
    }

    public function getGoldBalanceCache()
    {
        $lastTransaction = $this->golds()->latest('id')->select('balance')->first();
        return $lastTransaction->balance ?? 0;
    }

    public function getSuccessWithdrawSumAmountCache()
    {
        return $this->withdraws()->where('status', Withdraw::SUCCESS_WITHDRAW)->sum('amount');
    }

    public function getTodayWithdrawCache()
    {
        return $this->withdraws()->where('created_at', '>=', today())->first();
    }

    public function getDongdezhuanCache()
    {
        $user          = checkUser();
        $dongdezhuanId = $user->oauth()->where('oauth_type', 'dongdezhuan')->first()->oauth_id;
    }

    public function getTodayWithdrawLeftCache()
    {
        $count = 10;
        // if (!is_null($this->todayWithdraw)) {
        //     $count = 0;
        // }

        return $count;
    }

    public function getPlatformsCache()
    {
        return [
            'alipay' => empty($this->pay_account) ? null : $this->pay_account,
            'wechat' => empty($this->wechat_account) ? null : $this->wechat_account,
        ];
    }

    public function getBindPlatformsCache()
    {
        return [
            'alipay' => PayUtils::isAlipayOpenId($this->pay_account) ? $this->pay_account : null,
            'wechat' => empty($this->wechat_account) ? null : $this->wechat_account,
        ];
    }
}
