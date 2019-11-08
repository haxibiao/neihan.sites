<?php

namespace App\Listeners;

use App\Events\NewFollow;
use App\Follow;
use App\Notifications\UserFollowed;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewFollowNotification implements ShouldQueue
{
    public $delay = 60 * 10;
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     *
     * @param  NewFollow  $event
     * @return void
     */
    public function handle(NewFollow $event)
    {
        //TODO:: 汇总新关注通知
        $follow = $event->follow;
        $user   = $follow->user;

        $is_deFollow = Follow::onlyTrashed()->where([
            'user_id'       => $follow->user_id,
            'followed_type' => $follow->followed_type,
            'followed_id'   => $follow->followed_id,
        ])->exists();

        if ($follow->followed instanceof \App\User) {
            if (!$is_deFollow) {
                $follow->followed->notify(new UserFollowed($user));
            }
        }

    }
}
