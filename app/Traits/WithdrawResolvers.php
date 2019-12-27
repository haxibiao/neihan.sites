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
        $user   = getUser();
        $amount = $args['amount'];

        //可控制提现关闭
        stopfunction("提现");

        //禁止3元以上用户提现
        if ($amount > Withdraw::WITHDRAW_MAX) {
            throw new GQLException('等级和勋章不够哦~，请等待版本更新，增加更多玩法吧~');
        }

//        目前点墨阁被微信授权
        if ($args['platform'] === 'Wechat' && config('app.name_cn') !== '点墨阁'){
            throw new GQLException('微信提现正在开发,点墨阁可以体验微信提现哦~');
        }

        //钱包提现信息是否存在
        $wallet = $user->wallet;
        if (is_null($wallet->pay_account)) {
            throw new GQLException('提现失败, 请先完善提现资料!');
        }
        if ($user->isWithDrawTodayByPayAccount(now())) {
            throw new GQLException('您今日已经提现过了哦 ~，明天再来吧 ~');
        }

        //限制每日日提现上限
        $todayWithDrawAmout = $wallet->withdraws()
            ->where('status', '>', \App\Withdraw::FAILURE_WITHDRAW)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
        if ($todayWithDrawAmout >= Withdraw::WITHDRAW_MAX) {
            throw new GQLException('今日提现额度已达上限,明日再来哦~');
        }

        //最低提现额度
        if ($amount < Exchange::MIN_RMB) {
            throw new GQLException('提现失败,最低1元起提现！');
        }

        //是否超支
        if ($wallet->available_balance < $amount) {
            throw new GQLException('提现失败, 余额不足');
        }

        // 新用户不做限制
        $isWithdrawBefore = $user->isWithdrawBefore();
        if ($isWithdrawBefore) {
            $contribute      = $user->profile->count_contributes;
            $need_contribute = $amount * Contribute::WITHDRAW_DATE;
            $diffContributes = $need_contribute - $contribute;
            if ($contribute < $need_contribute) {
                throw new GQLException('您还需' . $diffContributes . '点贡献值就可以提现成功啦~，快多尝试发布优质原创视频吧~');
            }
        }

        $payId = $wallet->getPayId($args['platform']);
        $platform = $args['platform'];
        if(empty($payId)){
            throw_if($platform == Withdraw::ALIPAY_PLATFORM, GQLException::class, '提现失败,支付宝提现信息未绑定!');
            throw_if($platform == Withdraw::WECHAT_PLATFORM, GQLException::class, '提现失败,微信提现信息未绑定!');
        }


        //开启兑换事务,替换到钱包 创建提现订单
        if ($args['platform'] === 'dongdezhuan') {
            if ($user->checkUserIsBindDongdezhuan()) {
                $ddzUser  = $user->getDongdezhuanUser();
                $withdraw = $wallet->withdraw($amount, $ddzUser->account, 'dongdezhuan');
            } else {
                throw new GQLException('您还没有绑定懂得赚账户哦~');
            }
        }else{

            $withdraw = $wallet->withdraw($amount, $payId, $args['platform']);
        }

        if (!$withdraw) {
            throw new GQLException('兑换失败,请稍后再试!');
        }

        // 消耗贡献值
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
        $contribute      = $user->profile->count_contributes;
        $need_contribute = $amount * Contribute::WITHDRAW_DATE;
        $diffContributes = $need_contribute - $contribute;
        if ($diffContributes <= 0) {
            return 0;
        }
        return $diffContributes;
    }
}
