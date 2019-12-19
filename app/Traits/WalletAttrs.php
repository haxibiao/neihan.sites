<?php
namespace App\Traits;

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

    public function getTodayWithdrawLeftAttribute()
    {
        $count = 10;
        // if (!is_null($this->todayWithdraw)) {
        //     $count = 0;
        // }

        return $count;
    }
}
