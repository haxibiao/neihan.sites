<?php

namespace App\Observers;

use App\Notice;

class NoticeObserver
{
    public function creating(Notice $notice)
    {
        //默认发表用户为Root
        $notice->user_id = auth()->id() ?? 1;
    }

    /**
     * Handle the notice "created" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function created(Notice $notice)
    {
        //触发广播时间
    }

    /**
     * Handle the notice "updated" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function updated(Notice $notice)
    {
        //
    }

    /**
     * Handle the notice "deleted" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function deleted(Notice $notice)
    {
        //
    }

    /**
     * Handle the notice "restored" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function restored(Notice $notice)
    {
        //
    }

    /**
     * Handle the notice "force deleted" event.
     *
     * @param  \App\Notice  $notice
     * @return void
     */
    public function forceDeleted(Notice $notice)
    {
        //
    }
}
