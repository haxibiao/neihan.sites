<?php

namespace App;

use App\Exceptions\GQLException;
use App\Traits\WalletAttrs;
use App\Traits\WalletRepo;
use App\Traits\WalletResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use WalletResolvers;
    use WalletAttrs;
    use WalletRepo;

    protected $fillable = [
        'user_id',
        'type',
        'pay_account',
        'real_name',
        'pay_infos',
        'pay_info_change_count',
        'total_withdraw_amount',
        'wechat_account',
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
        return $this->hasMany(\App\Transaction::class);
    }

    //repo
    public static function rmbWalletOf(User $user): Wallet
    {
        $wallet = self::firstOrCreate([
            'user_id' => $user->id,
            'type'    => Wallet::RMB_WALLET,
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

    public function isCanWithdraw($amount)
    {
        return $this->availableBalance >= $amount;
    }


    public function scopeToday($query, $column = 'created_at')
    {
        return $query->where($column, '>=', today());
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

    public function golds()
    {
        //FIXME: 修复之前user_id对应的golds流水未对应的钱包的
        return $this->hasMany(\App\Gold::class);
    }
    //FIXME: rmb钱包对象负责： 充值，提现

    //FIXME: 金币钱包对象负责： 兑换，提现，转账(仅限打赏，付费问答时)

}
