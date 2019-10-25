<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'balance',
        'remark',
        'created_at',
        'updated_at',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(\App\Wallet::class);
    }

    public static function makeIncome($wallet, $amount, $remark = '智慧点兑换'): WalletTransaction
    {
        $balance = $wallet->balance + $amount;
        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }

    public static function makeOutcome($wallet, $amount, $remark = '提现'): WalletTransaction
    {
        $amount  = -1 * $amount;
        $balance = $wallet->balance + $amount;
        return WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => $remark,
        ]);
    }

    // 这个接口给测试人员用
    public function gaoQiao($rootValue, array $args, $context, $resolveInfo)
    {
        if ($args['password'] != 'hnhyhxb') {
            throw new GQLException('请求错误');
        }

        $wallet = getUser()->wallet;
        $amount = $args['amount'];

        $balance = $wallet->balance + $amount;
        $result  = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'balance'   => $balance,
            'remark'    => '刷钱测试功能',
        ]);
        // 这里不做 exchange 记录操作
        return $result;
    }
}
