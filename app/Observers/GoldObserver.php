<?php

namespace App\Observers;

use App\Gold;

class GoldObserver
{
    public function created(Gold $gold)
    {
        //更新懂得赚金币收益数据
        $user = $gold->user;
        $ddzUserId = $user->getDongdezhuanUserId();
        if(!$ddzUserId) {
           return;
        }
        $ddzUser =  \App\DDZ\User::find($ddzUserId);
        if(!$ddzUser) {
            return;
        }
        $app_name_cn = env('APP_NAME_CN');
        $appTask     = $ddzUser->appTasks()->whereAppName($app_name_cn)->first();
        if ($appTask) {
            $appTask->left_golds = $user->gold;
            $appTask->save();
        }
    }
}
