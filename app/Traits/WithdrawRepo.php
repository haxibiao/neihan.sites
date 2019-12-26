<?php

namespace App\Traits;

use App\Dongdezhuan\UserApp;
use App\Helpers\Pay\PayUtils;
use App\Transaction;
use App\User;
use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait WithdrawRepo
{

    //处理该笔提现
    public function process()
    {
        $wallet = $this->wallet;
        $user   = $this->wallet->user;
        //提现是否等待中
        if (!$this->isWaitingWithdraw()) {
            return;
        }

        if (!$user->isWithDrawTodayByPayAccount($this->created_at)) {
            return $this->illegalWithdraw('当前支付宝账号已经提现过了噢 ~，请勿重复提现~~');
        }

        //判断余额
        if ($wallet->balance < $this->amount) {
            return $this->illegalWithdraw('余额不足,非法订单！');
        }
        $transferResult = $this->makingAlipayTransfer();

        // 本地调试 模拟提现
        // $transferResult = ['order_id' => rand(10000000000, 100000000000), 'sub_msg' => '本地调试提现成功'];

        //账户余额不足 || 未知异常,直接退出
        if (empty($transferResult)) {
            return null;
        }

        if (isset($transferResult['order_id'])) {
            //转账成功
            $this->processingSucceededWithdraw($transferResult['order_id']);
        } else {
            //转账失败
            $remark = $transferResult['failed_msg'] ?? '系统错误,提现失败,请重新尝试！';
            $this->processingFailedWithdraw($remark);
        }

        //写入文件储存
        $this->writeWithdrawLog();
    }

    public function processDongdezhuan()
    {
        $wallet = $this->wallet;
        $user   = $this->wallet->user;

        //提现是否等待中
        if (!$this->isWaitingWithdraw()) {
            return;
        }

        if (!$user->isWithDrawTodayByPayAccount($this->created_at)) {
            return $this->illegalWithdraw('当前懂得赚账号已经提现过了噢 ~，请勿重复提现~~');
        }

        //判断余额
        if ($wallet->balance < $this->amount) {
            return $this->illegalWithdraw('余额不足,非法订单！');
        }

        $result = $this->makingDongdezhuanTransfer($user);

        if (isset($result['success'])) {
            //转账成功
            $this->processingSucceededWithdraw($result['success'], 'dongdezhuan', '转账到懂得赚成功');
        } else {
            //转账失败
            $remark = $result['error'] ?? '系统错误,提现失败,请重新尝试！';
            $this->processingFailedWithdraw($remark);
        }
    }

    /**
     * 支付宝转账
     *
     * @return array
     */
    private function makingAlipayTransfer()
    {
        $result   = [];
        $wallet   = $this->wallet;
        $outBizNo = $this->biz_no; //第三方交易流水
        $realName = $wallet->real_name; //用户真实姓名
        $remark   = sprintf('【%s】%s', config('app.name_cn'), '提现');
        $amount   = $this->amount;
        $platform = $this->to_platform;
        $payId    = $this->to_account;

        $payUtils = new PayUtils($platform);
        //支付宝转账 内部业务单号 收款人账号 收款人姓名 金额 备注
        try {
            $transferResult = $payUtils->transfer($outBizNo, $payId, $realName, $amount, $remark);
        } catch (\Exception $ex) {
            $transferResult = $ex->raw ?? null;
        }

        //写入日志
        Log::channel('withdraws')->info($transferResult);

        //处理支付响应
        if ($platform == Withdraw::WECHAT_PLATFORM) {
            //微信余额不足
            if (Arr::get($transferResult, 'err_code') != 'NOTENOUGH') {
                $result['order_id']   = $transferResult['payment_no'] ?? null;
                $result['failed_msg'] = $transferResult['err_code_des'] ?? null;
            }
        } else if ($platform == Withdraw::ALIPAY_PLATFORM) {
            //支付宝余额不足、转账失败
            if (isset($transferResult['alipay_fund_trans_toaccount_transfer_response'])) {
                $transferResult = $transferResult['alipay_fund_trans_toaccount_transfer_response'];
            }
            if (Arr::get($transferResult, 'sub_code') != 'PAYER_BALANCE_NOT_ENOUGH') {
                $result['order_id']   = $transferResult['order_id'] ?? null;
                $result['failed_msg'] = $transferResult['sub_msg'] ?? null;
            }
        }

        return $result;
    }

    private function makingDongdezhuanTransfer(User $user)
    {

        $result = array();
        $appId  = UserApp::checkApp();

        if ($appId === null) {
            $result['error'] = '当前App没有权限提现到懂得赚哦~';
            return $result;
        }

        $app     = $appId     = \App\Dongdezhuan\App::find($appId);
        $ddzUser = $user->getDongdezhuanUser();

        $remark = '从' . $app->name . '提现到懂得赚' . $this->amount . '元';

        try {
//            1.开启事务

            \DB::beginTransaction();

//            2.生成转账订单
            $order = \App\Dongdezhuan\Order::create([
                'user_id'     => $ddzUser->id,
                'app_id'      => $app->id,
                'app_user_id' => $user->id,
                'amount'      => $this->amount,
                'remark'      => $remark,
                'receipt'     => self::getOrderNum(),
            ]);

//            3.写入懂得赚转账流水记录
            \App\Dongdezhuan\Transaction::create([
                'wallet_id' => $ddzUser->wallet->id,
                'type'      => '转账',
                'status'    => '已支付',
                'remark'    => $app->name . '转账',
                'amount'    => $order->amount,
                'balance'   => $ddzUser->wallet->balance + $order->amount,
            ]);

//            4.转账
            $order->status = \App\Dongdezhuan\Order::STATUS_SUCCESS;
            $order->save();

            \DB::commit();

            $result['success'] = $order->receipt;
            return $result;
        } catch (\Exception $exception) {
            \DB::rollBack();
            info($exception->getMessage());
            $result['error'] = '转账失败,服务器打瞌睡了~';
            return $result;
        }

    }

    /**
     * 简单获取订单号
     * @return string
     */
    public static function getOrderNum()
    {
        $date = date('Ymd');
        $rand = substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 12);
        return $date . $rand;
    }

    /**
     * 处理成功提现
     *
     * @param string $orderId
     * @param string $to_platform
     * @param string $remark
     * @return void
     * @throws \Exception
     */
    protected function processingSucceededWithdraw($orderId, $to_platform = 'Alipay', $remark = '提现成功')
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->id);
        $wallet   = $withdraw->wallet;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status   = Withdraw::SUCCESS_WITHDRAW;
            $withdraw->trade_no = $orderId;
            $withdraw->remark   = $remark;

            //2.创建流水记录
            $transaction              = Transaction::makeOutcome($wallet, $withdraw->amount, $remark);
            $withdraw->transaction_id = $transaction->id;
            $withdraw->save();

            //更新交易总额
            $wallet->total_withdraw_amount = $wallet->success_withdraw_sum_amount;
            $wallet->save();

            DB::commit(); //事务提交
        } catch (\Exception $ex) {
            DB::rollback(); //数据回滚
        }
    }

    /**
     * 处理失败提现
     *
     * @param string $remark
     * @return void
     * @throws \Exception
     */
    protected function processingFailedWithdraw($remark = "")
    {
        //重新查询锁住该记录更新
        $withdraw = Withdraw::lockForUpdate()->find($this->id);
        $user     = $this->user;
        $wallet   = $this->wallet;

        DB::beginTransaction(); //开启事务
        try {
            //1.更改提现记录
            $withdraw->status = Withdraw::FAILURE_WITHDRAW;
            $withdraw->remark = $remark;
            $withdraw->save();

//            // 金额兑换智慧点
            //             $amount = $withdraw->amount;
            //             $gold   = Exchange::computeGold($amount);
            //
            //             //2.创建退款流水记录
            //             if (!is_null($wallet)) {
            //                 $transaction = WalletTransaction::makeOutcome($wallet, $amount, '提现失败');
            //             }
            //
            //             // 3.退回智慧点 创建兑换记录
            //             if (!is_null($user)) {
            //                 Gold::makeIncome($user, $gold, '提现失败退款');
            //                 $user->refresh();
            //                 Exchange::exhangeIn($user, $gold);
            //             }

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
        $withdraw         = $this;
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
        $withdraw = $this->refresh();

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
