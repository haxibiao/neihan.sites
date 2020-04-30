<?php

namespace App\Observers;

use App\UserLive;

class UserLiveObserver
{
    /**
     * Handle the user live "created" event.
     *
     * @param  \App\UserLive  $userLive
     * @return void
     */
    public function created(UserLive $userLive)
    {
        //触发直播任务更新和检查
        $user  = $userLive->user;
        $tasks = $user->getUserLiveTasks();
        foreach ($tasks as $task) {
            $task->checkTaskStatus($userLive->user);
        }
    }

    /**
     * Handle the user live "updated" event.
     *
     * @param  \App\UserLive  $userLive
     * @return void
     */
    public function updated(UserLive $userLive)
    {
        //
    }

    /**
     * Handle the user live "deleted" event.
     *
     * @param  \App\UserLive  $userLive
     * @return void
     */
    public function deleted(UserLive $userLive)
    {
        //
    }

    /**
     * Handle the user live "restored" event.
     *
     * @param  \App\UserLive  $userLive
     * @return void
     */
    public function restored(UserLive $userLive)
    {
        //
    }

    /**
     * Handle the user live "force deleted" event.
     *
     * @param  \App\UserLive  $userLive
     * @return void
     */
    public function forceDeleted(UserLive $userLive)
    {
        //
    }
}
