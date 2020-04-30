<?php

namespace App\Observers;

use App\Invitation;

class InvitationObserver
{
    /**
     * Handle the invitation "created" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function created(Invitation $invitation)
    {
        //更新用户在邀请任务方面的各任务的指派的状态
        $user  = $invitation->user;
        $tasks = $user->invitation_tasks;
        foreach ($tasks as $task) {
            $task->checkTaskStatus($user);
        }
    }

    /**
     * Handle the invitation "updated" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function updated(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the invitation "deleted" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function deleted(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the invitation "restored" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function restored(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the invitation "force deleted" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function forceDeleted(Invitation $invitation)
    {
        //
    }
}
