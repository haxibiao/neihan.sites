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

        //开启兑换事务,替换到钱包 创建提现订单
        $qb = $user->oauth()->where('oauth_type', 'dongdezhuan');
        if ($qb->exists()) {
            $withdraw = $wallet->withdraw($amount, $qb->first()->data['account']);
        } else {
            $withdraw = $wallet->withdraw($amount);
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
