<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $connection = 'dongdezhuan';

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class);
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class, 'to_user_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\User::class, 'from_user_id');
    }

    public static function makeIncome($wallet, $amount, $remark = '智慧点兑换', $type = '兑换', $status = '已兑换'): Transaction
    {
        $balance = $wallet->balance + $amount;
        return Transaction::create([
            'type'      => $type,
            'status'    => $status,
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }

}
