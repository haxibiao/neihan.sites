<?php

namespace App;

use App\Traits\WithdrawFacade;
use App\Traits\WithdrawRepo;
use App\Traits\WithdrawResolvers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use WithdrawResolvers, WithdrawRepo,WithdrawFacade;

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
        'user_id',
        'host',
    ];

    const MAX_WITHDRAW_SUM_AMOUNT = 30;
    //提现平台
    const ALIPAY_PLATFORM = 'alipay';
    const WECHAT_PLATFORM = 'wechat';

    //状态:提现成功 提现失败 待处理提现
    const SUCCESS_WITHDRAW = 1;
    const FAILURE_WITHDRAW = -1;
    const WAITING_WITHDRAW = 0;

    const WITHDRAW_MAX = 1;

    public function scopeToday($query, $column = 'created_at')
    {
        return $query->where($column, '>=', today());
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', self::SUCCESS_WITHDRAW);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

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


    public function isWaiting()
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

}
