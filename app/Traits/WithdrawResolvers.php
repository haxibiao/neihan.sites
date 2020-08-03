<?php

namespace App\Traits;

use App\Contribute;
use App\Exceptions\GQLException;
use App\Exchange;
use App\User;
use App\Version;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait WithdrawResolvers
{
    public function createWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        if ($user->status == User::STATUS_FREEZE) {
            throw new GQLException('账户异常,详情咨询QQ群:808982693');
        }
        $profile           = $user->profile;
        $amount            = $args['amount'];
        $platform          = $args['platform'];
        $useWithdrawBadges = Arr::get($args, 'useWithdrawBadges', false);
        $isWithdrawBefore  = $user->isWithdrawBefore();
        //1. 可控制提现关闭
        stopfunction("提现");

        //提现强制要求绑定手机号
        if (empty($user->phone)) {
            throw new GQLException("请先绑定手机号再来提现吧~");
        }

        //2. 判断版本号
        $version       = substr($profile->app_version, 0, 3);
        $latestVersion = Version::latest('name')->first();
        // if ($profile->app_version === null || !Str::contains($latestVersion->name, $version)) {
        //     throw new GQLException('当前版本过低,请更新后再尝试提现,详情咨询QQ群:808982693');
        // }

        throw_if($amount > 0.5, GQLException::class, '高额提现被抢光啦~');

        //检查有没有作弊，比如贡献值，金币获取时间都在同一时间
        $this->checkCheating($user);

        $this->checkHighWithdraw($user, $amount);
        //3. 用户钱包信息完整性校验
        $wallet = $user->wallet;

        $payId = $wallet->getPayId($platform);
        if (is_null($wallet)) {
            throw new GQLException('提现失败, 请先完善提现资料!');
        }
        if ($user->hasWithdrawToday()) {
            throw new GQLException('您今日已经提现过了哦 ~，明天再来吧 ~');
        }

        //5. 限制每日提现上限
        $this->checkUserToDayWithdrawAmount($wallet);
        //6. 最低提现额度
        if ($amount < Exchange::MIN_RMB) {
            throw new GQLException('提现失败,最低' . Exchange::MIN_RMB . '元起提现！');
        }

        $this->checkSystemWithdrawAmount();

        //7. 是否超支
        if ($wallet->available_balance < $amount) {
            throw new GQLException('提现失败, 余额不足');
        }

        $this->controlHighWithdrawAmount($amount);

        //8. 新用户不做限制

        $this->checkUserContribute($user, $amount);
        //9. 检查提现信息
        if ($platform !== 'dongdezhuan') {
            $this->checkPayWalletInfo($platform, $wallet);

            if ($isWithdrawBefore) {
                $contribute      = $user->getTodayContributeAttribute();
                $need_contribute = $amount * $user->getUserWithdrawDate();
                $diffContributes = $need_contribute - $contribute;
                if ($contribute < $need_contribute) {
                    throw new GQLException('今日贡献不足,您还需' . $diffContributes . '点日贡献值就可以提现成功啦~');
                }
            }

            $withdraw = $wallet->withdraw($amount, $payId, $platform);
            if (!$withdraw) {
                throw new GQLException('兑换失败,请稍后再试!');
            }
            return $withdraw;
        }
    }

    /**
     * 检查今日提现金额总数
     *
     * @param [Double] $amount
     * @return void
     * @throws UserException
     */
    public function checkSystemWithdrawAmount()
    {
        //总额（总提现金额，含队列内未成功的）
        $todayWithdrawAmount = Withdraw::today()->sum('amount');
        if ($todayWithdrawAmount >= Withdraw::MAX_WITHDRAW_SUM_AMOUNT) {
            throw new GQLException('今日提现总名额已用完,请明日9点再来哦');
        }
    }

    // 控制高额提现金额发放名额
    public function controlHighWithdrawAmount($amount)
    {
        //控制额度上限
        $todayAmountGroup = Withdraw::selectRaw('amount,count(*) as count')->today()->groupBy('amount')->get();
        foreach ($todayAmountGroup as $todayAmount) {
            if ($amount == $todayAmount->amount) {
                if ($todayAmount->amount == 3 && $todayAmount->count >= 5) {
                    throw new GQLException('提现失败,3元额度已用完,请提现其他额度哦！');
                }

                if ($todayAmount->amount == 5 && $todayAmount->count >= 2) {
                    throw new GQLException('提现失败,5元额度已用完,请提现其他额度哦！');
                }

                if ($todayAmount->amount == 10 && $todayAmount->count >= 1) {
                    throw new GQLException('提现失败,10元额度已用完,请提现其他额度哦！');
                }
            }
        }
    }

    public function checkUserToDayWithdrawAmount($wallet)
    {
        $todayWithDrawAmount = $wallet->withdraws()
            ->where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
            ->whereDate('created_at', today())
            ->sum('amount');
        if ($todayWithDrawAmount >= Withdraw::WITHDRAW_MAX) {
            throw new GQLException('今日提现额度已达上限,明日再来哦~');
        }
    }

    public function checkCheating($user)
    {
        $count = Contribute::where("user_id", $user->id)->where("created_at", ">=", now()->toDateString())->groupBy("created_at")->havingRaw("count(created_at)>1")->count();
        //有获取贡献时间重复的情况直接封号
        if ($count == 0) {
            return true;
        } else {
            $user->update(["status" => User::STATUS_FREEZE]);
            throw new GQLException('提现失败,账号行为异常~');
        }
    }

    public function checkHighWithdraw($user, $amount)
    {
        //高额度政策
        if ($amount > 0.3) {
            $hour   = now()->hour;
            $minute = now()->minute;

            if ($user->wallet->total_withdraw_amount > 2) {

                // 工作时间才可以提现
                if (($hour < 10 || $hour >= 18 || $minute >= 10)) {
                    throw new GQLException('提现的限量抢时间段在: 10:00-18:00,每个小时前10分钟内开抢,下次早点来哦~');
                }

                //新注册3小时内的用户不能高额提现，防止撸毛
                if (!now()->diffInHours($user->created_at) >= 3) {
                    throw new GQLException('当前限量抢额度已被抢光了,下个时段再试吧');
                }

                // throw_if($user->hasWithdrawToday(), GQLException::class, '今天已经提现过啦~');

                // 提现额度逻辑因为邀请下线，已弃用... 改为限制限量抢额度
                // 每人默认最高10元限量抢额度，以后邀请放开可提高,先简单防止老刷子账户疯狂并发提现...
                // $withdrawLines = $user->withdraw_lines;
                // if ($withdrawLines < $amount) {
                //     throw new GQLException('您的限量抢额度不足,请等新版本开放提额玩法');
                // }

                /**
                 * 限流:
                 * 每时段前10分钟，比如10:00 - 10:10 限制流量,避免DB SERVER 负载压力100%
                 * 限制几率 20%
                 * 时间超出过,恢复正常!
                 */

                if ($minute < 10) {
                    $rand = mt_rand(1, 10);
                    throw_if($rand <= 8, GQLException::class, '目前人数过多,请您下个时段(' . ($hour + 1) . '点)再试!');
                }
            }
        }
    }

    public function checkUserContribute($user, $amount)
    {
        //0.3元以上提现检查贡献
        if ($amount > 0.3) {
            $contribute = $user->getTodayContributeAttribute();
            $need_contribute = $amount * $user->getUserWithdrawDate();
            $diffContributes = $need_contribute - $contribute;
            if ($contribute < $need_contribute) {
                throw new GQLException('今日贡献不足,您还需' . $diffContributes . '点日贡献值就可以提现成功啦~');
            }
        }
    }

    public function checkPayWalletInfo($platform, $wallet)
    {
        $payId = $wallet->getPayId($platform);
        if (empty($payId)) {
            throw_if(strcasecmp($platform, Withdraw::ALIPAY_PLATFORM) == 0, GQLException::class, '提现失败,支付宝提现信息未绑定!');
            throw_if(strcasecmp($platform, Withdraw::WECHAT_PLATFORM) == 0, GQLException::class, '提现失败,微信提现信息未绑定!');
        }
    }

    public function resolveWithdraws($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event('钱包', "提现列表");
        return Withdraw::orderBy('id', 'desc')->where('wallet_id', $args['wallet_id']);
    }

    public function resolveWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_event('钱包', "提现详情");
        return Withdraw::find($args['id']);
    }

    public function CanWithdrawals($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user             = checkUser();
        $amount           = $args['amount'];
        $isWithdrawBefore = $user->isWithdrawBefore();

        if ($isWithdrawBefore) {
            $contribute      = $user->contribute;
            $need_contribute = $amount * $user->getUserWithdrawDate();;
            $diffContributes = $need_contribute - $contribute;
            if ($diffContributes <= 0) {
                return 0;
            }
            return $diffContributes;
        }

        return 0;
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

        $contribute = $user->getUserWithdrawDate();
        $isWithdrawBefore = $user->isWithdrawBefore();
        $hasWithdrawOnDDZ = $user->hasWithdrawOnDDZ();
        //  工厂内是否提现 || 懂得赚上是否提现
        if ($isWithdrawBefore || $hasWithdrawOnDDZ) {
            $minAmount = 1;
        } else {
            $minAmount = 0.3;
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
                'description'           => $contribute * 0.5 . '活跃',
                'tips'                  => '秒到账',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => null,
            ],
            [
                'amount'                => 1,
                'description'           => $contribute * 1 . '活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $user->doubleHighWithdrawCardsCount,

            ],
            [
                'amount'                => 3,
                'description'           => $contribute * 3 . '活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $user->fiveTimesHighWithdrawCardsCount,
            ],
            [
                'amount'                => 5,
                'description'           => $contribute * 5 . '活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $user->tenTimesHighWithdrawCardsCount,
            ],
        ];

        //        去掉头或尾部数据
        if (count($withdrawInfo) > 4) {
            if ($isWithdrawBefore || $hasWithdrawOnDDZ) {
                array_shift($withdrawInfo);
            } else {
                array_pop($withdrawInfo);
            }
        }

        return $withdrawInfo;
    }
}
