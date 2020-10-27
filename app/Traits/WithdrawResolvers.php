<?php

namespace App\Traits;

use App\Contribute;
use App\Jobs\ProcessWithdraw;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Haxibiao\Base\Exceptions\GQLException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait WithdrawResolvers
{
    public function createWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //throw_if(true, UserException::class, '提现正在维护中，望请谅解~');
        $user     = \getUser();
        throw_if($user->is_disable, GQLException::class, '账号异常!');

        $amount   = $args['amount'];
        $platform = $args['platform'];
        $wallet = $user->wallet;

        // 限制提现时间段
        Withdraw::checkWithdrawTime();

        // 检查时间和限量抢限流控制并发
        Withdraw::checkHighWithdraw($user, $amount);

        //        // 检查版本 2.9.7
        //        Withdraw::checkWithdrawVersion(getAppVersion());

        // 预防新用户快速请求提现，和一日重复提现
        Withdraw::checkLastWithdrawTime($wallet);

        //检查钱包绑定
        Withdraw::checkWalletInfo($wallet, $platform);

        // 成功提现次数限制
        if (!is_testing_env()) {
            throw_if(!$wallet->availableWithdrawCount, GQLException::class, '今日提次数已达上限!');
        }

        //提现老刷子随机提现
        if($wallet->total_withdraw_amount>10){
            throw_if(random_int(1,10)>2, GQLException::class, '当前提现人数过多，请晚些再来提现吧~');
        }

        // 可提现策略检查
        Withdraw::canWithdraw($user, $wallet, $amount, $platform);
        //如果是新人(未提现过），则预先进行余额转换
        if (!$user->isWithdrawBefore()) {
            $user->startExchageChangeToWallet();
        }

        if ($wallet->isCanWithdraw($amount)) {
            //创建提现记录
            $withdraw = Withdraw::createWithdrawWithWallet($wallet, $amount, $platform);
            //限量抢成功了，扣除限量抢额度
            if ($amount > 0.5) {
                $user->decrement('withdraw_lines', $amount);

                // 扣除贡献点
                //                $needContributes = User::getAmountNeedDayContributes($amount);
                //                Contribute::makeOutCome($user->id,$withdraw->id,$needContributes,'withdraws','提现兑换');

                //加入延时1小时提现队列
                dispatch_now(new ProcessWithdraw($withdraw))->delay(now()->addMinutes(rand(50, 60))); //不再手快者得

            } else {
                //加入秒提现队列
                dispatch_now(new ProcessWithdraw($withdraw));
            }
            return $withdraw;
        } else {
            throw new GQLException('账户余额不足,请稍后再试!');
        }
    }

    public function resolveWithdraws($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event("提现列表", 'list_withdraws', getUserId());
        return Withdraw::orderBy('id', 'desc')->where('wallet_id', $args['wallet_id']);
    }

    public function resolveWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event("提现详情", 'show_withdraw', $args['id']);
        return Withdraw::find($args['id']);
    }

    /**
     *
     * 1.新人首次提现0.3元：
     * 前端：新人未提现前展示0.3/1/3/5，提现1次后展示1/3/5/10
     * 2.用户第二次提现引导下载懂得赚：
     * 后端：用户首次可提现0.3（无门槛），第二次触发提现，强制下载懂得赚，并到懂得赚上才能提现（配合前端弹窗）。
     * @param $rootValue
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return array
     */
    public function getWithdrawAmountList($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $user = checkUser();

        $contribute       = Contribute::WITHDRAW_DATE;
        $isWithdrawBefore = $user ? $user->isWithdrawBefore() : false;
        //  工厂内是否提现 || 懂得赚上是否提现
        if ($isWithdrawBefore) {
            $minAmount = 1;
        } else {
            $minAmount = 0.3;
        }
        $tenTimesHighWithdrawCardsCount = 0;
        $fiveTimesHighWithdrawCardsCount = 0;
        $doubleHighWithdrawCardsCount = 0;
        if ($user) {
            $tenTimesHighWithdrawCardsCount = $user->tenTimesHighWithdrawCardsCount;;
            $fiveTimesHighWithdrawCardsCount = $user->fiveTimesHighWithdrawCardsCount;
            $doubleHighWithdrawCardsCount = $user->doubleHighWithdrawCardsCount;
        }

        $withdrawInfo = [
            [
                'amount'                => $minAmount,
                'description'           => '新人福利',
                'tips'                  => '秒到账',
                'fontColor'             => '#FFA200',
                'bgColor'               => '#EF514A',
                'highWithdrawCardsRate' => null,
            ],
            [
                'amount'                => 0.5,
                'description'           => $contribute * 0.5 . '日活跃',
                'tips'                  => '秒到账',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => null,
            ],
            [
                'amount'                => 1,
                'description'           => $contribute * 1 . '日活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $doubleHighWithdrawCardsCount,

            ],
            [
                'amount'                => 3,
                'description'           => $contribute * 3 . '日活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $fiveTimesHighWithdrawCardsCount,
            ],
            [
                'amount'                => 5,
                'description'           => $contribute * 5 . '日活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $tenTimesHighWithdrawCardsCount,
            ],
        ];

        //        去掉头或尾部数据
        if (count($withdrawInfo) > 4) {
            if ($isWithdrawBefore) {
                array_shift($withdrawInfo);
            } else {
                array_pop($withdrawInfo);
            }
        }

        return $withdrawInfo;
    }
}
