<?php

namespace App\Traits;

use haxibiao\helpers\Pay\PayUtils;
use App\Withdraw;

trait WalletAttrs
{

    public function getBalanceAttribute()
    {
        $lastTransaction = $this->transactions()->latest('id')->select('balance')->first();
        return $lastTransaction->balance ?? 0;
    }

    public function getAvailableBalanceAttribute()
    {
        $availableBalance = $this->balance - $this->withdraws()
            ->where('status', Withdraw::WAITING_WITHDRAW)
            ->sum('amount');

        return $availableBalance;
    }

    public function getGoldBalanceAttribute()
    {
        $lastTransaction = $this->golds()->latest('id')->select('balance')->first();
        return $lastTransaction->balance ?? 0;
    }

    public function getSuccessWithdrawSumAmountAttribute()
    {
        return $this->withdraws()->where('status', Withdraw::SUCCESS_WITHDRAW)->sum('amount');
    }

    public function getTodayWithdrawAttribute()
    {
        return $this->withdraws()->where('created_at', '>=', today())->first();
    }

    public function getDongdezhuanAttribute()
    {
        $user          = checkUser();
        $dongdezhuanId = $user->oauth()->where('oauth_type', 'dongdezhuan')->first()->oauth_id;
    }

    public function getTodayWithdrawLeftAttribute()
    {
        $count = 10;
        // if (!is_null($this->todayWithdraw)) {
        //     $count = 0;
        // }

        return $count;
    }

    public function getPlatformsAttribute()
    {
        return [
            'alipay' => empty($this->pay_account) ? null : $this->pay_account,
            'wechat' => empty($this->wechat_account) ? null : $this->wechat_account,
        ];
    }

    public function getBindPlatformsAttribute()
    {
        return [
            'alipay' => PayUtils::isAlipayOpenId($this->pay_account) ? $this->pay_account : null,
            'wechat' => empty($this->wechat_account) ? null : $this->wechat_account,
        ];
    }
}
