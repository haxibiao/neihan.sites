<?php

namespace App\Jobs;

use App\Transaction;
use App\User;

use App\Withdraw;
use Carbon\Carbon;
use Exception;
use Haxibiao\Helpers\utils\PayUtils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessWithdrawNow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $withdrawId;

    protected $withdraw;

    protected $realName;

    protected $user;

    protected $wallet;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($withdrawId, $realName)
    {
        $this->withdrawId = $withdrawId;
        $this->realName = $realName;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        $withdraw = $this->initWithdraw($this->withdrawId);
        $user     = $this->user;
        $wallet   = $this->wallet;

        //如果发送异常 退回原金额
        $amount = $withdraw->amount;


        //提现待处理 && 非刷子 && 测试号
        if ($withdraw->isWaitingWithdraw()) {

            //判断余额
            if ($wallet->balance < $withdraw->amount) {
                return $this->IllegalWithdraw('余额不足,非法订单！');
            }

            $transferResult = $this->makingTransfer($withdraw, $wallet, $this->realName);
            //未知异常,直接处理完成
            if (empty($transferResult)) {
                return null;
            }

            if (isset($transferResult['order_id'])) {
                //转账成功
                $this->processingSucceededWithdraw($transferResult['order_id']);
                $this->withdraw->remark = '提现成功';
                $this->withdraw->save();
            } else {
                //转账失败
                $remark = $transferResult['failed_msg'] ?? '系统错误,提现失败,请重新尝试！';
                $this->processingFailedWithdraw($remark, $amount);
            }

            //通知用户
            // event(new WithdrawalDone($withdraw));
            //写入文件储存
            $this->writeWithdrawStorage();
        }
    }

    /**
     * 初始化提现信息
     *
     * @param [Int] $withdrawId
     * @return Withdraw
     */
    private function initWithdraw($withdrawId)
    {
        $withdraw = Withdraw::find($withdrawId);
        if (!is_null($withdraw)) {
            $this->withdraw = $withdraw;
            $this->wallet   = $this->withdraw->wallet;
            $this->user     = $this->wallet->user;
        }

        return $withdraw;
    }


    private function makingTransfer($withdraw, $wallet, $realName)
    {
        $result   = null;
        $outBizNo = $withdraw->biz_no;
        $platform = $withdraw->to_platform;
        $remark   = sprintf('【%s】%s', config('app.name_cn'), '提现');
        //收取5%手续费
        $amount   = round($withdraw->amount * 0.95);
        $payId    = $withdraw->to_account;

        //自己平台提现
        try {

            //支付宝、微信平台提现
            $result = $this->transferPayPlatform($outBizNo, $payId, $realName, $amount, $remark, $platform);
        } catch (Exception $ex) {
            $result = null;
            Log::channel('withdraws')->error($ex);
        }

        return $result;
    }


    //成功的提现
    private function processingSucceededWithdraw($orderId)
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->withdraw->id);
        $wallet   = $withdraw->wallet;
        $user = $wallet->user;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status   = Withdraw::SUCCESS_WITHDRAW;
            $withdraw->trade_no = $orderId;
            // $withdraw->to_platform = 'Alipay';

            //2.创建流水记录
            $transaction              = Transaction::makeOutcome($wallet, $withdraw->amount, $user->id, '提现');
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

    //失败的提现
    private function processingFailedWithdraw($remark, $amount)
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->withdraw->id);
        $wallet   = $this->wallet;
        $user = $wallet->user;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status = Withdraw::FAILURE_WITHDRAW;
            $withdraw->remark = $remark;
            $withdraw->save();

            //2.创建流水记录
            Transaction::makeOutcome($wallet, $amount, $user->id, '提现失败');
            //3. 提现失败将余额打回
            Transaction::makeOutcome($wallet, -1 * $amount, $user->id, '金额返回');;

            DB::commit(); //事务提交
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollback(); //数据回滚
        }
    }


    private function IllegalWithdraw($remark)
    {
        $withdraw         = $this->withdraw;
        $withdraw->status = Withdraw::FAILURE_WITHDRAW;
        $withdraw->remark = $remark;
        $withdraw->save();
    }


    private function writeWithdrawStorage()
    {
        $withdraw = $this->withdraw;

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




    public function transferPayPlatform($outBizNo, $payId, $realName, $amount, $remark, $platform)
    {
        $result = [];
        //转账
        $payUtils = new PayUtils($platform);
        try {
            $transferResult = $payUtils->transfer($outBizNo, $payId, $realName, $amount, $remark);
        } catch (\Exception $ex) {
            $transferResult = $ex->raw ?? null;
        }

        //处理支付响应
        if ($platform == Withdraw::WECHAT_PLATFORM) {
            //微信余额不足
            if (Arr::get($transferResult, 'err_code') != 'NOTENOUGH') {
                $result['order_id']   = $transferResult['payment_no'] ?? null;
                $result['failed_msg'] = $transferResult['err_code_des'] ?? null;
            }
        } else if ($platform == Withdraw::ALIPAY_PLATFORM) {
            //支付宝余额不足、转账失败
            if (isset($transferResult['alipay_fund_trans_uni_transfer_response'])) {
                $transferResult = $transferResult['alipay_fund_trans_uni_transfer_response'];
            }

            if (Arr::get($transferResult, 'sub_code') != 'PAYER_BALANCE_NOT_ENOUGH') {
                $result['order_id']   = $transferResult['order_id'] ?? null;
                $result['failed_msg'] = $transferResult['sub_msg'] ?? null;
            }
        }

        return $result;
    }
}
