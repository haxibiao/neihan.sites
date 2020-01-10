<?php

namespace App\DDZ;

use App\Exceptions\GQLException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{

    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'user_id',
        'type',
        'pay_account',
        'real_name',
        'pay_infos',
        'pay_info_change_count',
        'total_withdraw_amount',
    ];

    protected $casts = [
        'balance'   => 'double',
        'pay_infos' => 'array',
    ];

    //提现资料更改上限
    const PAY_INFO_CHANGE_MAX = 3;

    //钱包类型
    const RMB_WALLET  = 0; //RMB钱包
    const GOLD_WALLET = 1; //金币钱包
    const UNION_WALLET = 2; //联盟(邀请)钱包

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function withdraws(): HasMany
    {
        return $this->hasMany(\App\DDZ\Withdraw::class);
    }

    public function transactions():HasMany
    {
        return $this->hasMany(\App\DDZ\Transaction::class);
    }

    public function golds():HasMany
    {
        return $this->hasMany(\App\DDZ\Gold::class);
    }


    //repo
    public static function rmbWalletOf(\App\DDZ\User $user): \App\DDZ\Wallet
    {
        $wallet = self::firstOrCreate([
            'user_id' => $user->id,
            'type'    => 0,
        ]);
        return $wallet;
    }

    public static function goldWalletOf(\App\DDZ\User $user): \App\DDZ\Wallet
    {
        $wallet = self::firstOrCreate([
            'user_id' => $user->id,
            'type'    => 1,
        ]);
        return $wallet;
    }

    public function createWithdraw($amount)
    {
        if ($this->available_balance < $amount) {
            throw new GQLException('余额不足');
        }

        $withdraw = \App\DDZ\Withdraw::create([
            'wallet_id'  => $this->id,
            'amount'     => $amount,
            'to_account' => $this->pay_account,
        ]);
        return $withdraw;
    }

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
}
