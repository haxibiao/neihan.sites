<?php

namespace App;

use App\Model;

//FIXME: 也许需要修复WalletTransaction表的数据过来
class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'from_user_id',
        'to_user_id',
        'relate_id',
        'type',
        'remark',
        'log',
        'amount',
        'status',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function toUser()
    {
        return $this->belongsTo(\App\User::class, 'to_user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(\App\User::class, 'from_user_id');
    }

    public function tip()
    {
        return $this->belongsTo(\App\Tip::class, 'relate_id');
    }

    //repo
    public static function makeIncome($wallet, $amount, $remark = '智慧点兑换'): Transaction
    {
        $balance = $wallet->balance + $amount;
        return Transaction::create([
            'type'      => '兑换',
            'status'    => '已兑换',
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }

    public static function makeOutcome($wallet, $amount, $remark = '提现'): Transaction
    {
        $amount  = -1 * $amount;
        $balance = $wallet->balance + $amount;
        return Transaction::create([
            'type'      => '提现',
            'status'    => '已支付',
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }
}
