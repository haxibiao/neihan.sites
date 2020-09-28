<?php

namespace App\Traits;

use App\User;
use App\Withdraw;
use Haxibiao\Base\Exceptions\GQLException;

trait WithdrawFacade
{
    /**
     * 从某个钱包提现
     */
    public static function createWithdrawWithWallet($wallet, $amount, $platform)
    {
        return Withdraw::create([
            'wallet_id'   => $wallet->id,
            'amount'      => $amount,
            'to_account'  => $wallet->getPayId($platform),
            'to_platform' => $platform,
        ]);
    }

    public static function checkWithdrawTime()
    {
        $hour = now()->hour;
        if (($hour < 10 || $hour >= 23)) {
            throw new GQLException('提现时间段：10:00-23:00');
        }
    }

    // 风控手段: 限制允许发起限量抢提现的时间
    public static function checkHighWithdraw($user, $amount)
    {
        //高额提现
        if ($amount > 0.5) {

            if ($user->withdraw_at && $user->withdraw_at > today()) {
                throw new GQLException('一天只能提现1次哦~');
            }

            $hour   = now()->hour;
            $minute = now()->minute;

            //新注册3小时内的用户不能高额提现，防止撸毛
            if (!now()->diffInHours($user->created_at) >= 3) {
                throw new GQLException('当前限量抢额度已被抢光了,下个时段再试吧');
            }

            //老用户 工作时间才可以提现
            if (($hour < 10 || $hour >= 18 || $minute >= 40)) {
                throw new GQLException('限量抢时间段：10:00-18:00，请在每个小时开始的0-40分钟内开抢哦');
            }

            //每人默认最高10元限量抢额度，新版本开放提额玩法,先简单防止老刷子账户疯狂并发提现...
            $withdrawLines = $user->withdraw_lines;
            if ($withdrawLines < $amount) {
                throw new GQLException('限量抢额度已被抢光了,下个时段再试吧');
            }

            /**
             * 限流:
             * 每时段前10分钟，比如10:00 - 10:10 限制流量,避免DB SERVER 负载压力100%
             * 限制几率 95%
             * 时间超出过,恢复正常!
             */
            if ($minute < 40) {
                $rand = mt_rand(1, 100);
                // sleep(1); //不能sleep!! 会占用 php-fpm 和 mysql connections...
                throw_if($rand <= 30, GQLException::class, '目前人数过多,请您下个时段(' . ($hour + 1) . '点)再试!');
            }

        }
    }

    public static function checkWithdrawVersion($version)
    {
        if (!$version) {
            $version = getAppVersion();
        }
        throw_if($version < '2.9.7', GQLException::class, '版本过低,请升级最新版本提现!');
    }

    //预防新用户快速请求, 预防一日重复提现
    public static function checkLastWithdrawTime($wallet)
    {
        if (empty($wallet)) {
            throw new GQLException('您的提现速度过快,请稍后再试!');
        }

        $lastWithdraw = $wallet->withdraws()->latest('id')->first();
        if ($lastWithdraw && $lastWithdraw->created_at) {

            //测试UT时无需卡5秒
            if (!is_testing_env()) {
                $validSecond    = 5 - now()->diffInSeconds($lastWithdraw->created_at);
                $canNotWithdraw = now() > $lastWithdraw->created_at && $validSecond < 0;
                if (!$canNotWithdraw) {
                    throw new GQLException(sprintf('您的提现速度过快,请%s秒后再试!', $validSecond));
                }
            }
            if ($lastWithdraw->created_at > today()) {
                throw new GQLException("您今日已提交过提现请求");
            }
        }

        return true;
    }

    public static function checkWalletInfo($wallet, $platform)
    {
        throw_if(empty($wallet), GQLException::class, '您还没有绑定支付宝或微信哦!快去绑定吧!');

        $payId = $wallet->getPayId($platform);

        switch ($platform) {
            case Withdraw::ALIPAY_PLATFORM:
                $brand = '支付宝';
                break;
            case Withdraw::WECHAT_PLATFORM:
                $brand = '微信';
                break;
            default:
                $brand = '';
                break;
        }
        $errorMsg = sprintf('%s提现信息未绑定!', $brand);

        throw_if(empty($payId), GQLException::class, $errorMsg);
    }

    //能否提现策略
    public static function canWithdraw(User $user, $wallet, $amount, $platform)
    {
        //提现允许范围
        throw_if(!in_array($amount, Withdraw::getAllowAmount()), GQLException::class, '您还没有选择金额哦~');

        //小额提现
        if ($amount < 1) {
            throw_if($amount == 0.1 && $platform != Withdraw::ALIPAY_PLATFORM, GQLException::class, '0.1元只可提现至支付宝!');
        }

        //账户异常
        throw_if($user->isShuaZi, GQLException::class, '账户异常,请联系官方QQ群589454410');

        //高额度政策（1元以上都算，目前日提0.5了）
        if ($amount >= 1) {
            //限制总额度100元
            self::checkTodayWithdrawAmount($amount);
            //当天注册,禁止提现3元以上
            throw_if($user->created_at >= today(), GQLException::class, '今日提现已达上限!');
        }

        //贡献点检查
        //提现成功0.3元以上的，不再无门槛
//        if ($user->successWithdrawAmount >= 0.3) {
//            $needContributes = User::getAmountNeedDayContributes($amount);
//            $leftContributes = $needContributes - $user->week_contribute;
//            //无法完成该额度提现，提示需要的贡献值
//            if ($leftContributes > 0) {
//                $remark = sprintf('还差%s周贡献', $leftContributes);
//                throw new GQLException($remark);
//            }
//        }
    }

    public static function getAllowAmount()
    {
        return [0.1, 0.3, 0.5, 0.7, 1, 3, 5, 10];
    }

    public static function checkTodayWithdrawAmount($amount)
    {
        //总额（总提现金额，含队列内未成功的）
        $todayWithdrawAmount = Withdraw::today()->sum('amount');
        if ($todayWithdrawAmount >= Withdraw::MAX_WITHDRAW_SUM_AMOUNT) {
            throw new GQLException('今日提现总名额已用完,请明日10点再来哦');
        }

        //控制额度上限
        $todayAmountGroup = Withdraw::selectRaw('amount,count(*) as count')->today()->groupBy('amount')->get();
        foreach ($todayAmountGroup as $todayAmount) {
            if ($amount == $todayAmount->amount) {
                if ($todayAmount->amount == 3 && $todayAmount->count >= 30) {
                    throw new GQLException('3元限量额度已抢完,请提现其他额度哦!');
                }

                if ($todayAmount->amount == 5 && $todayAmount->count >= 10) {
                    throw new GQLException('5元限量额度已抢完,请提现其他额度哦!');
                }

                if ($todayAmount->amount == 10 && $todayAmount->count >= 5) {
                    throw new GQLException('10元限量额度已抢完,请提现其他额度哦!');
                }
            }
        }
    }
}
