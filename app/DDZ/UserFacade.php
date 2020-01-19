<?php

namespace App\DDZ;

use App\Exceptions\GQLException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

class UserFacade extends Facade
{
    //统计当日喝水任务次数
    public static function countHeshuiTaskDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDDZUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward1 = $count;
            $appTask->save();
        }
    }

    //统计当日睡觉任务次数
    public static function countShuijiaoTaskDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDDZUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward2 = $count;
            $appTask->save();
        }
    }

    //统计当日看视频次数
    public static function countRewardVideoDone(\App\User $user, $count)
    {
        $ddzUser     = $user->getDDZUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->done_reward3 = $count;
            $appTask->save();
        }
    }

    //好友贡献分红
    public static function bonusOnUserContribute(\App\User $user, \App\Contribute $contribute)
    {
        $me = $user->getDDZUser();
        //单次贡献时间，计算活跃贡献分红到懂得赚上级用户
        //60贡献 = 0.2元
        $price        = 0.2 / 60;
        $rewardAmount = round($price * $contribute->amount, 2);
        //最小0.01元
        if ($rewardAmount > 0) {
            $invitation = Invitation::hasBeInvitation($me->id);
            if (!is_null($invitation)) {
                $invitation->bonus($rewardAmount);
            }
        }
    }

    //处理所有金币变化到懂得赚
    public static function updateGold(\App\User $user, \App\Gold $gold)
    {
        $me          = $user->getDDZUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $me->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->left_golds = $user->gold;
            $appTask->save();
        }
    }

    //当用户在工厂APP贡献发生变化时，懂得赚的所有逻辑处理
    public static function updateContribute(\App\User $user, \App\Contribute $contribute)
    {
        $me          = $user->getDDZUser();
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $me->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            //TODO: 后面通过 $contribute->total 来获取减轻mysql压力
            $appTask->total_contribute = $user->total_contribute; //面板冗余的各工厂总贡献，为用户在厂长面板显示总贡献用
            $appTask->today_contribute = $user->today_contribute; //日贡献统计到面板，还没具体使用场景
            $appTask->save();
        }

        //厂长面板的总贡献数
        $invitation = Invitation::where('be_inviter_id', $me->id)->first();
        if ($invitation) {
            $invitation->update(['total_contribute' => $me->appTasks()->sum('total_contribute')]);
        }
    }

    //获取懂得赚账户
    public static function getUser($uuid)
    {
        if (empty($uuid)) {
            throw new \Exception('uuid为空，不能绑定或返回ddz用户');
        }
        $ddzUser = User::firstOrNew([
            'uuid' => $uuid,
        ], [
            'uuid'      => $uuid,
            'name'      => User::DEFAULT_NAME,
            'api_token' => str_random(60),
            'avatar'    => User::AVATAR_DEFAULT,
        ]);
        if ($ddzUser->id) {
            return $ddzUser;
        }
        $ddzUser->save();
        UserApp::bind($ddzUser->id); //TODO: 一直没明白这个绑定关系是什么意义？到处维护...
        return $ddzUser;
    }

    //提现到懂得赚
    public static function withdraw(\App\User $user, $amount, $order_num)
    {
        $result = array();

        //FIXME: 检查提现开关，广韵做了，在Nova... 去检查下他的代码 曾达威

        $ddzUser = $user->getDDZUser();
        $remark  = '从' . env('APP_NAME_CN') . '提现到懂得赚' . $amount . '元';

        try {
            \DB::beginTransaction();

            // 1.转账订单
            $app   = App::where('name', env('APP_NAME_CN'))->first();
            $order = \App\DDZ\Order::create([
                'user_id'     => $ddzUser->id,
                'app_id'      => $app->id,
                'app_user_id' => $user->id,
                'amount'      => $amount,
                'remark'      => $remark,
                'receipt'     => $order_num,
                'status'      => \App\DDZ\Order::STATUS_SUCCESS,
            ]);

            // 2.流水记录
            \App\DDZ\Transaction::create([
                'wallet_id' => $ddzUser->wallet->id,
                'type'      => '转账',
                'status'    => '已支付',
                'remark'    => $app->name . '转账',
                'amount'    => $amount,
                'balance'   => $ddzUser->wallet->balance + $amount,
            ]);

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

    //使用高额提现令牌
    public static function useHighWithdrawBadge(\App\User $user, $amount)
    {
        $me      = $user->getDDZUser();
        $product = Product::whereType(Product::TYPE_BADGE)
            ->whereLimit($amount)
            ->first();
        $userProduct = UserProduct::where('product_id', $product->id)
            ->where('user_id', $me->id)
            ->first();
        if (is_null($userProduct)) {
            throw new GQLException('您的【' . $amount . '元高额提现令牌】不足');
        }
        //扣除高额提现令牌
        $userProduct->delete();
    }

    //使用限量抢赔率卷
    public static function useHighWithdrawCard(\App\User $user)
    {
        $me       = $user->getDDZUser();
        $products = $me->products()
            ->where(function ($query) {
                $query->whereDate('user_products.expired_at', Carbon::now())
                    ->whereIn('user_products.scope', ['all', env('APP_NAME')]);
            })
            ->whereType(Product::TYPE_CARD)
            ->pluck('rate');

        //无限量抢券
        if ($products->isEmpty()) {
            throw new GQLException('当前额度不可提，您可以去懂得赚购买【高额提现令牌】或【限量抢倍率券】~');
        }

        //计算叠加倍率
        $totalRate = 1;
        $products->each(function ($product) use (&$totalRate) {
            $totalRate = $totalRate * $product;
        });
        return $totalRate;
    }

    //获得高额提现令牌数
    public static function countByHighWithdrawBadgeCount(\App\User $user, int $amount)
    {
        $me = $user->getDDZUser();
        return $me->products()->whereType(Product::TYPE_BADGE)
            ->whereLimit($amount)
            ->count();
    }

    //计算限量抢倍率
    public static function countByHighWithdrawCardsRate(\App\User $user)
    {
        $me       = $user->getDDZUser();
        $products = $me->products()
            ->where(function ($query) {
                $query->whereDate('user_products.expired_at', Carbon::now())
                    ->whereIn('user_products.scope', ['all', env('APP_NAME')]);
            })
            ->whereType(Product::TYPE_CARD)
            ->pluck('rate');
        if ($products->isEmpty()) {
            return 0;
        }
        //计算叠加倍率
        $totalRate = 1;
        $products->each(function ($product) use (&$totalRate) {
            $totalRate = $totalRate * $product;
        });

        return $totalRate;
    }

    //发放邀请奖励
    public static function makeInvitationReawrd($user, float $rewardAmount, $remark = '好友贡献奖励')
    {
        //找到发展钱包
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id, 'type' => Wallet::UNION_WALLET]);
        Transaction::makeIncome($wallet, $rewardAmount, $remark, '奖励');
    }

}
