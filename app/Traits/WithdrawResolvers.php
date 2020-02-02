<?php
namespace App\Traits;

use App\Contribute;
use App\DDZ\User as DDZUser;
use App\Exceptions\GQLException;
use App\Exchange;
use App\Version;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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

        //1. 可控制提现关闭
        stopfunction("提现");

        //2. 判断版本号
        $version       = substr($profile->app_version, 0, 3);
        $latestVersion = Version::latest('name')->first();
        if ($profile->app_version === null || !Str::contains($latestVersion->name, $version)) {
            throw new GQLException('当前版本过低,请更新后再尝试提现,详情咨询QQ群:808982693');
        }

        //3. 用户钱包信息完整性校验
        $wallet = $user->wallet;
        if (is_null($wallet)) {
            throw new GQLException('提现失败, 请先完善提现资料!');
        }
        if ($user->isWithDrawTodayByPayAccount(now())) {
            throw new GQLException('您今日已经提现过了哦 ~，明天再来吧 ~');
        }

        //4. 非首次提现,只许提现到懂得赚
        $isWithdrawBefore = $user->isWithdrawBefore();
        if ($isWithdrawBefore && $platform !== 'dongdezhuan') {
            throw new GQLException('温馨提示:提现系统全面升级,请下载懂得赚提现哦~,不仅高收益秒提现还不限时');
        }

        //5. 限制每日提现上限
        $todayWithDrawAmout = $wallet->withdraws()
            ->where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
        if ($todayWithDrawAmout >= Withdraw::WITHDRAW_MAX) {
            throw new GQLException('今日提现额度已达上限,明日再来哦~');
        }

        //6. 最低提现额度
        if ($amount < Exchange::MIN_RMB) {
            throw new GQLException('提现失败,最低' . Exchange::MIN_RMB . '元起提现！');
        }

        //7. 是否超支
        if ($wallet->available_balance < $amount) {
            throw new GQLException('提现失败, 余额不足');
        }

        //8. 新用户不做限制
        if ($isWithdrawBefore) {
            $contribute      = $user->getTodayContributeAttribute();
            $need_contribute = $amount * Contribute::WITHDRAW_DATE;
            $diffContributes = $need_contribute - $contribute;
            if ($contribute < $need_contribute) {
                throw new GQLException('今日贡献不足,您还需' . $diffContributes . '点日贡献值就可以提现成功啦~');
            }
        }

        //9. 懂得赚提现单独操作
        if ($platform !== 'dongdezhuan') {
            $payId = $wallet->getPayId($platform);
            if (empty($payId)) {
                throw_if($platform == Withdraw::ALIPAY_PLATFORM, GQLException::class, '提现失败,支付宝提现信息未绑定!');
                throw_if($platform == Withdraw::WECHAT_PLATFORM, GQLException::class, '提现失败,微信提现信息未绑定!');
            }
        }

        //高额提现 amount > 1
        $totalRate = null; //null代表高额提现令牌的概率，必中
        if ($amount > Withdraw::WITHDRAW_MAX) {
            //高额提现只限3，5，10元
            if (!in_array($amount, [3, 5, 10])) {
                throw new GQLException('当前版本过低,请更新后再尝试提现,详情咨询QQ群:808982693');
            }

            //高额提现令牌
            if ($useWithdrawBadges) {
                //高额提现令牌每天只有一位用户能成功..... 用户辛苦拿到令牌和总贡献，最后失败会崩溃.....
                // $todayWithdrawSuccessCount = Withdraw::where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
                //     ->whereIsNull('rate')
                //     ->whereDate('created_at', Carbon::today())
                //     ->whereAmount($amount)
                //     ->count();
                // if ($todayWithdrawSuccessCount > 0) {
                //     throw new GQLException('今日系统发放的高额令牌名额已用完~');
                // }

                //FIXME: 使用高额提现令这里没看懂...
                //使用高额提现令
                \DDZUser::useHighWithdrawBadge($user, $amount);

            } else {
                //限量抢倍率卷
                $totalRate = \DDZUser::useHighWithdrawCard($user);
            }
        }

        //10. 开启兑换事务,替换到钱包 创建提现订单
        if ($platform === 'dongdezhuan') {
            //处理限量抢倍率
            $ddzUser  = $user->getDDZUser();
            $withdraw = $wallet->withdraw($amount, $ddzUser->account, 'dongdezhuan', $totalRate);
        } else {
            $withdraw = $wallet->withdraw($amount, $payId, $platform);
        }

        if (!$withdraw) {
            throw new GQLException('兑换失败,请稍后再试!');
        }

        //FIXME: 之前消耗日贡献去提现的减贡献的数据，需要删除掉，减贡献今后仅限被惩罚场景...
        // 11. 消耗贡献值 ? 不用了，直接判断日贡献就够了
        // if ($isWithdrawBefore) {
        //     $user->consumeContributeToWithdraw($amount, "withdraws", $withdraw->id);
        // }
        return $withdraw;
    }

    public function resolveWithdraws($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user("提现列表", 'list_withdraws', getUserId());
        return Withdraw::orderBy('id', 'desc')->where('wallet_id', $args['wallet_id']);
    }

    public function resolveWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        app_track_user("提现详情", 'show_withdraw', $args['id']);
        return Withdraw::find($args['id']);
    }

    public function CanWithdrawals($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user             = checkUser();
        $amount           = $args['amount'];
        $isWithdrawBefore = $user->isWithdrawBefore();

        if ($isWithdrawBefore) {
            $contribute      = $user->contribute;
            $need_contribute = $amount * Contribute::WITHDRAW_DATE;
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

        $contribute       = Contribute::WITHDRAW_DATE;
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
                'amount'                => 1,
                'description'           => $contribute . '日活跃',
                'tips'                  => '秒到账',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => null,
            ],
            [
                'amount'                => 3,
                'description'           => $contribute * 3 . '日活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $user->doubleHighWithdrawCardsCount,

            ],
            [
                'amount'                => 5,
                'description'           => $contribute * 5 . '日活跃',
                'tips'                  => '限量抢',
                'fontColor'             => '#A0A0A0',
                'bgColor'               => '#FFBB04',
                'highWithdrawCardsRate' => $user->fiveTimesHighWithdrawCardsCount,
            ],
            [
                'amount'                => 10,
                'description'           => $contribute * 10 . '日活跃',
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
