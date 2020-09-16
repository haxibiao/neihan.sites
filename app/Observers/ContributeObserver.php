<?php

namespace App\Observers;

use App\Contribute;

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
        //更新user表上的冗余字段
        $user = $contribute->user;
        //更新任务状态
        $user->reviewTasksByClass(get_class($contribute));
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
