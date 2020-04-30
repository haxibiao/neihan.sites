<?php

namespace App;

use App\Traits\WithdrawRepo;
use App\Traits\WithdrawResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use WithdrawResolvers, WithdrawRepo;

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

    //提现平台
    const ALIPAY_PLATFORM = 'Alipay';
    const WECHAT_PLATFORM = 'Wechat';

    //状态:提现成功 提现失败 待处理提现
    const SUCCESS_WITHDRAW = 1;
    const FAILURE_WITHDRAW = -1;
    const WAITING_WITHDRAW = 0;

    const MAX_WITHDRAW_SUM_AMOUNT = 200;

    const WITHDRAW_MAX = 1;

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(\App\Wallet::class);
    }

    // attrs

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

    public function getBizNoAttribute()
    {
        //拼接格式 年月日时分秒 + 提现订单号
        return $this->created_at->format('YmdHis') . $this->id;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
