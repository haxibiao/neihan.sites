<?php

namespace App\Listeners;


use App\Gold;
use Illuminate\Support\Carbon;

class ShareableWasVisitedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /**
         * https://pm.jinlinle.com/browse/YXSP-6
         */
        // 分享者
        $user  = getUser();
        $share = $event->share;
        $shareUser = $share->user;

        // 观看分享视频的是否为新注册用户
        $isNewUser = $user->created_at->isToday();

        if($isNewUser){
            // $user        被邀请人   + 150金币
            Gold::makeIncome($user,150,'视频分享奖励');
            // $shareUser   邀请人     + 50金币
            Gold::makeIncome($shareUser,50,'视频分享奖励');
        } else {
            // $user        被分享人   + 10金币
            Gold::makeIncome($user,10,'视频分享奖励');
            // $shareUser   分享人     + 15金币
            Gold::makeIncome($shareUser,15,'视频分享奖励');
        }
    }
}
