<?php

namespace App\Observers;

use App\Contribute;
use App\DDZ\InvitationPhase;
use App\DDZ\Transaction;
use App\DDZ\UserInvitation;
use App\DDZ\Wallet;

class ContributeObserver
{
    /**
     * Handle the contribute "created" event.
     *
     * @param  \App\Contribute  $contribute
     * @return void
     */
    public function created(Contribute $contribute)
    {
        //分红到懂得赚
        //60贡献 = 0.2元
        if ($contribute->amount > 0) {
            $price        = 0.2 / 60;
            $rewardAmount = round($price * $contribute->amount, 2);
            //最小0.01元
            if ($rewardAmount > 0) {
                $user    = $contribute->user;
                $ddzUser = $user->getDongdezhuanUser();
                $inviter = $ddzUser->myInviter;
                if (!is_null($inviter)) {

                    //分红倍率
                    $userInvitation = UserInvitation::firstOrCreate(['user_id' => $inviter->id], ['phase_id' => InvitationPhase::DEFAULT_PHASE_ID]);
                    $rate           = $userInvitation->rate;
                    if ($rate > 0) {
                        $rewardAmount *= $rate;
                    }

                    //暂时写死0.05分成
                    $rewardAmount = 0.05;

                    //找到他的上级
                    $wallet = Wallet::firstOrCreate(['user_id' => $inviter->id, 'type' => Wallet::UNION_WALLET]);
                    Transaction::makeIncome($wallet, $rewardAmount, '好友活跃奖励', '奖励');
                }
            }
        }
    }

    /**
     * Handle the contribute "updated" event.
     *
     * @param  \App\Contribute  $contribute
     * @return void
     */
    public function updated(Contribute $contribute)
    {
        //
    }

    /**
     * Handle the contribute "deleted" event.
     *
     * @param  \App\Contribute  $contribute
     * @return void
     */
    public function deleted(Contribute $contribute)
    {
        //
    }

    /**
     * Handle the contribute "restored" event.
     *
     * @param  \App\Contribute  $contribute
     * @return void
     */
    public function restored(Contribute $contribute)
    {
        //
    }

    /**
     * Handle the contribute "force deleted" event.
     *
     * @param  \App\Contribute  $contribute
     * @return void
     */
    public function forceDeleted(Contribute $contribute)
    {
        //
    }
}
