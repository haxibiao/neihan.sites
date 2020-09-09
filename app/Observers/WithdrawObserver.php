<?php

namespace App\Observers;

use App\Withdraw;

class WithdrawObserver
{
    /**
     * Handle the withdraw "created" event.
     *
     * @param  \App\Withdraw  $withdraw
     * @return void
     */
    public function created(Withdraw $withdraw)
    {
        $wallet = $withdraw->wallet;
        $user   = $wallet->user;
        $user->withdrawAt();
    }

    /**
     * Handle the withdraw "updated" event.
     *
     * @param  \App\Withdraw  $withdraw
     * @return void
     */
    public function updated(Withdraw $withdraw)
    {
        $wallet = $withdraw->wallet;
        $user   = $wallet->user;
        $user->reviewTasksByClass(get_class($withdraw));
    }

}
