<?php

namespace App\Traits;

use App\Contribute;
use App\User;

trait ContributeRepo
{
    public static function rewardUserContribute($user_id, $id, $amount, $type, $remark)
    {
        $contribute = Contribute::create(
            [
                'user_id'          => $user_id,
                'contributed_id'   => $id,
                'contributed_type' => $type,
                'remark'           => $remark,
                'amount'           => $amount,
            ]
        );
        $contribute->recountUserContribute();
        return $contribute;
    }

    //获取今日的某类型$type的奖励次数
    public static function getTodayCountByType(string $type, User $user)
    {
        return Contribute::where([
            'contributed_type' => $type,
            'user_id'          => $user->id,
        ])->whereRaw("created_at  >= curdate()")->count();
    }

    //获取今日的某类型$type 和锁定的$id(比如某文章的) 的奖励次数
    public static function getTodayCountByContributed(string $type, $id, User $user)
    {
        return Contribute::where([
            'contributed_type' => $type,
            'contributed_id'   => $id,
            'user_id'          => $user->id,
        ])->whereDate('created_at', today())->count();
    }

}
