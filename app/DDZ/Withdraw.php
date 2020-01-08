<?php

namespace App\DDZ;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{

    protected $connection = 'dongdezhuan';

    protected $fillable = [
        'wallet_id',
        'status',
        'transaction_id',
        'amount',
        'remark',
        'trade_no',
        'to_account',
        'to_platform',
        'created_at',
        'updated_at',
    ];

    //状态:提现成功 提现失败 待处理提现
    const SUCCESS_WITHDRAW = 1;
    const FAILURE_WITHDRAW = -1;
    const WAITING_WITHDRAW = 0;

    const WITHDRAW_MAX = 1;

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(\App\DDZ\Wallet::class);
    }

    public function isSuccessWithdraw(): bool
    {
        return $this->status === self::SUCCESS_WITHDRAW;
    }

    public function isWaitingWithdraw(): bool
    {
        return $this->status === self::WAITING_WITHDRAW;
    }

    public function isFailureWithdraw(): bool
    {
        return $this->status === self::FAILURE_WITHDRAW;
    }

    public function getBizNoAttribute(): bool
    {
        //拼接格式 年月日时分秒 + 提现订单号
        return $this->created_at->format('YmdHis') . $this->id;
    }
}
