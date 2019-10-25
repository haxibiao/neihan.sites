<?php
namespace App\Traits;

use App\Exceptions\GQLException;
use App\Wallet;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait WalletMutator
{
    public function setWalletPayment($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $user = getUser();

        $real_name = trim($args['real_name']);

        if (!preg_match_all("/([\x{4e00}-\x{9fa5}]+)/u", $real_name)) {
            throw new GQLException('姓名输入不合法,请重新输入!');
        }
        $wallet = Wallet::firstOrNew(['user_id' => $user->id]);
        $wallet->pay_account = $args['pay_account'];
        $wallet->real_name   = $args['real_name'];

        //更新一下提现变更记录
        $payInfos          = $wallet->pay_infos;
        $payInfo           = Arr::only($args, ['real_name', 'pay_account']);
        $payInfo['time']   = now()->toDateTimeString();
        $payInfos[]        = $payInfo;
        $wallet->pay_infos = $payInfos;

        $wallet->type                  = 0;
        $wallet->total_withdraw_amount = 0;
        $wallet->save();

        return $wallet;
    }
}
