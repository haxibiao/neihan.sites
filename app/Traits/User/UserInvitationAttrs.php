<?php

namespace App\Traits;

use App\Exchange;
use App\Transaction;
use Illuminate\Support\Arr;

trait UserInvitationAttrs
{
    public function getTodayIncomeAttribute()
    {
        $inCome      = 0;
        $unionWallet = $this->unionWallet;
        if (!is_null($unionWallet)) {
            $inCome = Transaction::where('wallet_id', $unionWallet->id)->whereDate('created_at', today())->sum('amount');
        }

        return $inCome;
    }

    public function getPreRewardAmountAttribute()
    {
        $count = $this->invitations()->whereNull('invited_in')->count();

        return $count * 1.2;
    }

    public function getInviteCodeAttribute()
    {
        $code      = $this->user_id;
        $minLength = 6;
        while (strlen($code) < $minLength) {
            $code = '0' . $code;
        }

        return $code;
    }

    public function getInviteSloganAttribute()
    {
        $inviteCode = $this->invite_code;
        $brand      = config('app.name_cn');
        $url        = $this->invite_url;
        $user       = $this->user;

        $wallet = $user->wallet;
        if ($wallet) {
            $amount = $wallet->total_withdraw_amount;
        } else {
            //转换智慧点
            $amount = Exchange::computeAmount($this->gold);
        }

        //最小0.9元
        if ($amount < 1) {
            $amount = 0.9;
        }
        $amount = sprintf("%.1f", $amount);
        $slogan = sprintf('我在%s这款APP上疯狂赚钱中，累计赚了%s元，(复制【%s】)打开%s安装%s，高收益秒提现，快来一起赚钱吧~', $brand, $amount, $inviteCode, $url, $brand);

        return $slogan;
    }

    public function getRateAttribute()
    {
        $rate = Arr::get($this->attributes, 'rate', 0);
        //倍率
        if ($rate <= 0) {
            $phase = $this->phase;
            if (!is_null($phase)) {
                $rate       = $phase->rate;
                $this->rate = $rate;
                $this->save();
            }
        }

        return (float) $rate;
    }

    public function getNextIncrementAttribute()
    {
        $time      = $this->next_increment_at;
        $timestamp = is_null($time) ? 0 : $time->timestamp;

        $data = [
            'next_increment_at'           => $time,
            'next_increment_at_timestamp' => $timestamp,
        ];

        return $data;
    }

    public function getInviteUrlAttribute()
    {
        $make = is_prod_env() ? 'secure_url' : 'url';
        $url  = $make('i/' . $this->invite_code);

        return $url;
    }
}
