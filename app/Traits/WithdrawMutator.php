<?php
namespace App\Traits;

use App\Exceptions\GQLException;
use App\Exchange;
use App\Gold;
use App\Jobs\ProcessWithdraw;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait WithdrawMutator
{
    public function createWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user   = getUser();
        $amount = $args['amount'];


        //1.禁止3元以上用户提现
        if ($amount > Withdraw::WITHDRAW_MAX) {
              throw new GQLException('提现失败，今日业务繁忙，限制' . Withdraw::WITHDRAW_MAX . '元以上提现');
        }

        //2.钱包提现信息是否存在
        $wallet = $user->wallet;
        if (is_null($wallet)) {
            throw new GQLException('提现失败, 请先完善提现资料!');
        }

        //限制每日日提现上限
        $todayWithDrawAmout = $wallet->withdraws()
            ->where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
            ->whereDate('created_at',Carbon::today())
            ->sum('amount');
        if ($todayWithDrawAmout >= Withdraw::WITHDRAW_MAX) {
            throw new GQLException('今日提现额度已达上限,明日再来哦~');
        }

        //3.最低提现额度
        if ($amount < Exchange::MIN_RMB) {
            throw new GQLException('提现失败,最低1元起提现！');
        }

        //4.是否超支
        if ($wallet->available_balance < $amount) {
            throw new GQLException('提现失败, 余额不足');
        }

        $exchangeStatus = false;
        //3.开启兑换事务,替换到钱包 创建提现订单
        DB::beginTransaction();
        try {
            //钱包扣款进行提现
            $withdraw = $wallet->createWithdraw($amount);

            //提交事务
            DB::commit();
            $exchangeStatus = true;
        } catch (\Exception $ex) {
            $exchangeStatus = false;
            DB::rollBack(); //数据库回滚
            Log::error($ex);
        }

        if (!$exchangeStatus) {
            throw new GQLException('兑换失败,请稍后再试!');
        }

        dispatch(new ProcessWithdraw($withdraw->id))->onQueue('withdraws');

        return $withdraw;
    }
}
