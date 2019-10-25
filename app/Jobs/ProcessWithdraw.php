<?php

namespace App\Jobs;

use App\Helpers\Pay\Alipay\AlipayService;
use App\WalletTransaction;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessWithdraw implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $withdraw;

    protected $wallet;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($withdrawId)
    {
        $withdraw = Withdraw::find($withdrawId);
        if (is_null($withdraw)) {
            return;
        }

        $this->withdraw = $withdraw;
        $this->wallet   = $this->withdraw->wallet;
        $this->user     = $this->wallet->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $withdraw = $this->withdraw;
        $wallet   = $this->wallet;

        //提现是否等待中
        if (!$withdraw->isWaitingWithdraw()) {
            return;
        }

        //判断余额
        if ($wallet->balance < $withdraw->amount) {
            return $this->illegalWithdraw('余额不足,非法订单！');
        }

        $transferResult = $this->makingAlipayTransfer();
        //账户余额不足 || 未知异常
        if (array_get($transferResult, 'sub_code') == 'PAYER_BALANCE_NOT_ENOUGH' || empty($transferResult)) {
            return null;
        }

        if (isset($transferResult['order_id'])) {
            //转账成功
            $this->processingSucceededWithdraw($transferResult['order_id']);
        } else {
            //转账失败
            $remark = $transferResult['sub_msg'] ?? '系统错误,提现失败,请重新尝试！';
            $this->processingFailedWithdraw($remark);
        }

        //写入文件储存
        $this->writeWithdrawLog();
    }

    /**
     * 支付宝转账
     *
     * @return array
     */
    private function makingAlipayTransfer()
    {
        $outBizNo = $this->withdraw->biz_no;
        $account  = $this->wallet->pay_account;
        $realName = $this->wallet->real_name;
        $remark   = sprintf('【%s】%s', config('app.name_cn'), '提现');
        $amount   = $this->withdraw->amount;

        //支付宝转账 内部业务单号 收款人账号 收款人姓名 金额 备注

        $alipayTransferResponse = AlipayService::transfer($outBizNo, $account, $realName, $amount, $remark);

        info($alipayTransferResponse);

        return $alipayTransferResponse['alipay_fund_trans_toaccount_transfer_response'] ?? null;
    }

    /**
     * 处理成功提现
     *
     * @param string $orderId
     * @return void
     */
    protected function processingSucceededWithdraw($orderId)
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->withdraw->id);
        $user     = $this->user;
        $wallet   = $withdraw->wallet;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status      = Withdraw::SUCCESS_WITHDRAW;
            $withdraw->trade_no    = $orderId;
            $withdraw->to_platform = 'Alipay';
            $withdraw->remark      = '提现成功';

            //2.创建流水记录
            $transaction              = WalletTransaction::makeOutcome($wallet, $withdraw->amount, '提现');
            $withdraw->transaction_id = $transaction->id;
            $withdraw->save();

            //更新交易总额
            $wallet->total_withdraw_amount = $wallet->success_withdraw_sum_amount;
            $wallet->save();

            DB::commit(); //事务提交
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollback(); //数据回滚
        }
    }

    /**
     * 处理失败提现
     *
     * @param string $remark
     * @return void
     */
    protected function processingFailedWithdraw($remark = "")
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->withdraw->id);
        $user     = $this->user;
        $wallet   = $this->wallet;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status = Withdraw::FAILURE_WITHDRAW;
            $withdraw->remark = $remark;
            $withdraw->save();

            //金额兑换智慧点
            // $amount = $withdraw->amount;
            // $gold   = Exchange::computeGold($amount);

            // //2.创建退款流水记录
            // if (!is_null($wallet)) {
            //     $transaction = WalletTransaction::makeOutcome($wallet, $amount, '提现失败');
            // }

            // // 3.退回智慧点 创建兑换记录
            // if (!is_null($user)) {
            //     Gold::makeIncome($user, $gold, '提现失败退款');
            //     $user->refresh();
            //     Exchange::exhangeIn($user, $gold);
            // }

            //事务提交
            DB::commit();
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollback(); //数据回滚
        }
    }

    /**
     * 非法订单,余额不足
     *
     * @param string $remark
     * @return void
     */
    private function illegalWithdraw($remark)
    {
        $withdraw         = $this->withdraw;
        $withdraw->status = Withdraw::FAILURE_WITHDRAW;
        $withdraw->remark = $remark;
        $withdraw->save();
    }

    /**
     * 写入提现日志
     *
     * @return void
     */
    private function writeWithdrawLog()
    {
        $withdraw = $this->withdraw->refresh();

        $log = 'Withdraw ID:' . $withdraw->id . ' 账号:' . $withdraw->to_account . '  ';

        if ($withdraw->isSuccessWithdraw()) {
            $log .= '提现成功(交易单号:' . $withdraw->trade_no . ')';
        } else if ($withdraw->isFailureWithdraw()) {
            $log .= '提现失败(' . $withdraw->remark . ')';
        } else {
            return;
        }

        //写入到文件中记录 格式 withdraw/2018-xx-xx
        $file = 'withdraw/' . Carbon::now()->toDateString();

        if (!Storage::exists($file)) {
            Storage::makeDirectory('withdraw');
        }

        Storage::disk('local')->append($file, $log);
    }
}
