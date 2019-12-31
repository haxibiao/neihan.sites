<?php
namespace App\Traits;

use App\Contribute;
use App\Exceptions\GQLException;
use App\Exchange;
use App\Withdraw;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Carbon;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait WithdrawResolvers
{
    public function createWithdraw($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user     = getUser();
        $amount   = $args['amount'];
        $platform = $args['platform'];

        //1. 可控制提现关闭
        stopfunction("提现");

        //2. 禁止3元以上用户提现
        if ($amount > Withdraw::WITHDRAW_MAX) {
            throw new GQLException('等级和勋章不够哦~，请等待版本更新，增加更多玩法吧~');
        }

        //3. 目前只有点墨阁被微信授权提现
        if ($platform === 'Wechat' && config('app.name_cn') !== '点墨阁') {
            throw new GQLException('微信提现正在开发,点墨阁可以体验微信提现哦~');
        }

        //4. 用户钱包效验
        $wallet = $user->wallet;
        if (is_null($wallet)) {
            throw new GQLException('提现失败, 请先完善提现资料!');
        }
        if ($user->isWithDrawTodayByPayAccount(now())) {
            throw new GQLException('您今日已经提现过了哦 ~，明天再来吧 ~');
        }


        // 非首次提现,只许提现到懂得赚
        $isWithdrawBefore = $user->isWithdrawBefore();
//        if ($isWithdrawBefore && $platform !== 'dongdezhuan'){
//            throw new GQLException('温馨提示:提现系统全面升级,下载懂得赚:高收益秒提现不限时');
//        }

        //5. 限制每日日提现上限
        $todayWithDrawAmout = $wallet->withdraws()
            ->where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        if ($todayWithDrawAmout >= Withdraw::WITHDRAW_MAX) {
            throw new GQLException('今日提现额度已达上限,明日再来哦~');
        }

        //6. 最低提现额度
        if ($amount < Exchange::MIN_RMB) {
            throw new GQLException('提现失败,最低'.Exchange::MIN_RMB.'元起提现！');
        }

        //7. 是否超支
        if ($wallet->available_balance < $amount) {
            throw new GQLException('提现失败, 余额不足');
        }

        //8. 新用户不做限制

        if ($isWithdrawBefore) {
            $contribute      = $user->contribute;
            $need_contribute = $amount * Contribute::WITHDRAW_DATE;
            $diffContributes = $need_contribute - $contribute;
            if ($contribute < $need_contribute) {
                throw new GQLException('您还需' . $diffContributes . '点贡献值就可以提现成功啦~，快多尝试发布优质原创视频吧~');
            }
        }

//      9. 懂得赚提现单独操作
        if ($platform !== 'dongdezhuan'){
            $payId = $wallet->getPayId($platform);
            if (empty($payId)) {
                throw_if($platform == Withdraw::ALIPAY_PLATFORM, GQLException::class, '提现失败,支付宝提现信息未绑定!');
                throw_if($platform == Withdraw::WECHAT_PLATFORM, GQLException::class, '提现失败,微信提现信息未绑定!');
            }
        }


        //10. 开启兑换事务,替换到钱包 创建提现订单
        if ($platform === 'dongdezhuan') {
            if ($user->checkUserIsBindDongdezhuan()) {
                $ddzUser  = $user->getDongdezhuanUser();
                $withdraw = $wallet->withdraw($amount, $ddzUser->account, 'dongdezhuan');
            } else {
                throw new GQLException('您还没有绑定懂得赚账户哦~');
            }
        } else {
            $withdraw = $wallet->withdraw($amount, $payId, $platform);
        }

        if (!$withdraw) {
            throw new GQLException('兑换失败,请稍后再试!');
        }

        // 11. 消耗贡献值
        if ($isWithdrawBefore) {
            $user->consumeContributeToWithdraw($amount, "withdraws", $withdraw->id);
        }
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
        $user            = checkUser();
        $amount          = $args['amount'];
        $contribute      = $user->contribute;
        $need_contribute = $amount * Contribute::WITHDRAW_DATE;
        $diffContributes = $need_contribute - $contribute;
        if ($diffContributes <= 0) {
            return 0;
        }

        return $diffContributes;
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
    public function getWithdrawAmountList($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo){

        $user = checkUser();

        $contribute = Contribute::WITHDRAW_DATE;
        $successWithdrawAmount = $user->getUserSuccessWithdrawAmount();

        //            判断是否提现过
        if ($user->isWithdrawBefore()){
            $minAmount = 1;
        }else{
            $minAmount = 0.3;
        }

        $withdrawInfo = [
            [
                'amount'      => $minAmount,
                'description' => '新人福利',
                'tips'        => '秒到账',
                'fontColor'   => '#FFA200',
                'bgColor'     => '#EF514A',
            ],
            [
                'amount'      => 1,
                'description' => $contribute . '日贡献',
                'tips'        => '秒到账',
                'fontColor'   => '#A0A0A0',
                'bgColor'     => '#FFBB04',
            ],
            [
                'amount'      => 3,
                'description' => $contribute * 3 . '日贡献',
                'tips'        => '秒到账',
                'fontColor'   => '#A0A0A0',
                'bgColor'     => '#FFBB04',
            ],
            [
                'amount'      => 5,
                'description' => $contribute * 5 . '日贡献',
                'tips'        => '秒到账',
                'fontColor'   => '#A0A0A0',
                'bgColor'     => '#FFBB04',
            ],
            [
                'amount'      => 10,
                'description' => $contribute * 10 . '日贡献',
                'tips'        => '秒到账',
                'fontColor'   => '#A0A0A0',
                'bgColor'     => '#FFBB04',
            ],
        ];

//        去掉头或尾部数据
        if (count($withdrawInfo) > 4) {
            if ($successWithdrawAmount > 1) {
                array_shift($withdrawInfo);
            } else {
                array_pop($withdrawInfo);
            }
        }

        return $withdrawInfo;
    }
}
