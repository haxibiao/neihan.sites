<?php

namespace App;

use App\Exceptions\GQLException;
use App\Traits\WalletMutator;
use App\WalletTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use WalletMutator;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class);
    }

    public function withdraws(): HasMany
    {
        return $this->hasMany(\App\Withdraw::class);
    }

    public function transactions()
    {
        return $this->hasMany(\App\WalletTransaction::class);
    }

    public function getBalanceAttribute()
    {
        $lastTransaction = $this->transactions()->latest('id')->select('balance')->first();
        return $lastTransaction->balance ?? 0;
    }

    public function getAvailableBalanceAttribute()
    {
        $availableBalance = $this->balance - $this->withdraws()
            ->where('status', \App\Withdraw::WAITING_WITHDRAW)
            ->sum('amount');

        return $availableBalance;
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

    public function createWithdraw($amount)
    {
        if ($this->available_balance < $amount) {
            throw new GQLException('余额不足');
        }

        $withdraw = Withdraw::create([
            'wallet_id'  => $this->id,
            'amount'     => $amount,
            'to_account' => $this->pay_account,
        ]);
        return $withdraw;
    }
}
