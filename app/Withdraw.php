<?php

namespace App;

use App\Traits\WithdrawMutator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use WithdrawMutator;

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

    const WITHDRAW_MAX = 3;

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(\App\Wallet::class);
    }

    // methods

    public function isSuccessWithdraw()
    {
        return $this->status == self::SUCCESS_WITHDRAW;
    }

    public function isWaitingWithdraw()
    {
        return $this->status == self::WAITING_WITHDRAW;
    }

    public function isFailureWithdraw()
    {
        return $this->status == self::FAILURE_WITHDRAW;
    }

    //attributes

    public function getBizNoAttribute()
    {
        //拼接格式 年月日时分秒 + 提现订单号
        return $this->created_at->format('YmdHis') . $this->id;
    }
}
