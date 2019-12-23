<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Exchange;
use App\Gold;
use App\Jobs\ProcessWithdraw;
use App\Transaction;
use App\User;
use App\Wallet;
use App\Withdraw;

trait WalletRepo
{
    //FIXME: rmb钱包对象负责： 收入,提现
    //TODO: 充值
    public function changeRMB($amount, $remark)
    {
        $balance = $this->balance + $amount;
        return Transaction::create([
            'user_id'   => $this->user_id,
            'wallet_id' => $this->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }

    public function withdraw($amount,$to_account = null): Withdraw
    {
        if ($this->available_balance < $amount) {
            throw new GQLException('余额不足');
        }

        $withdraw = Withdraw::create([
            'wallet_id'  => $this->id,
            'amount'     => $amount,
            'to_account' => $to_account ?? $this->pay_account,
        ]);
        //交给提现队列
        dispatch(new ProcessWithdraw($withdraw->id))->onQueue('withdraws');
        return $withdraw;
    }

    //FIXME: 金币钱包对象负责： 收入，兑换
    //TODO: 转账(仅限打赏，付费问答时)

    public function changeGold($gold, $remark)
    {
        $goldBalance = $this->goldBalance + $gold;
        $gold        = Gold::create([
            'user_id'   => $this->user_id,
            'wallet_id' => $this->id,
            'gold'      => $gold,
            'balance'   => $goldBalance,
            'remark'    => $remark,
        ]);
        //更新user表上的冗余字段
        $this->user->update(['gold' => $goldBalance]);
        return $gold;
    }

    //金币钱包，兑换rmb
    public function exchange($rmb)
    {
        $gold = Exchange::computeGold($rmb);

        //扣除gold
        $this->changeGold(-$gold, "兑换");
        //记录兑换
        $exchange = Exchange::exchangeOut($this->user, $gold);

        //钱包收入
        $wallet = $this->user->wallet; //默认钱包
        $wallet->changeRMB($exchange->rmb, '兑换收入');
    }

    public static function rmbWalletOf(User $user): Wallet
    {
        $wallet = self::firstOrCreate([
            'user_id' => $user->id,
            'type'    => 0,
        ]);
        return $wallet;
    }

    public static function goldWalletOf(User $user): Wallet
    {
        $wallet = self::firstOrCreate([
            'user_id' => $user->id,
            'type'    => 1,
        ]);
        return $wallet;
    }

}
