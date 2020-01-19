<?php

namespace App\Observers;

use App\Contribute;
use DDZUser;
use Exception;
use Illuminate\Support\Facades\Log;

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
        try {
            $user = $contribute->user;

            if ($contribute->amount > 0) {
                //计算分红
                DDZUser::bonusOnUserContribute($user, $contribute);
            }
            //上报用户贡献到懂得赚
            DDZUser::updateContribute($user, $contribute);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
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
