<?php

namespace App\Observers;

use App\Events\NewFollow;
use App\Follow;

class FollowObserver
{
    /**
     * Handle the follow "created" event.
     *
     * @param  \App\Follow  $follow
     * @return void
     */
    public function created(Follow $follow)
    {
        event(new NewFollow($follow));
        //同步用户关注数
        $user                            = $follow->user;
        $user->profile->count_followings = $user->followings()->count();
        $user->profile->save();

        //同步被关注着的粉丝数
        $count = Follow::where('followed_type', $follow->followed_type)->where("followed_id", $follow->followed_id)->count();
        if ($follow->followed_type == 'users') {
            $follow->followed->profile->update(['count_follows' => $count]);
        } else {
            $follow->followed->update(['count_follows' => $count]);
        }
    }

    /**
     * Handle the follow "updated" event.
     *
     * @param  \App\Follow  $follow
     * @return void
     */
    public function updated(Follow $follow)
    {
        //
    }

    /**
     * Handle the follow "deleted" event.
     *
     * @param  \App\Follow  $follow
     * @return void
     */
    public function deleted(Follow $follow)
    {
        //同步用户关注数
        $user                            = $follow->user;
        $user->profile->count_followings = $user->followings()->count();
        $user->profile->save();

        //同步被关注着的粉丝数
        $count = Follow::where('followed_type', $follow->followed_type)->where("followed_id", $follow->followed_id)->count();
        if ($follow->followed_type == 'users') {
            $follow->followed->profile->update(['count_follows' => $count]);
        } else {
            $follow->followed->update(['count_follows' => $count]);
        }
    }

    /**
     * Handle the follow "restored" event.
     *
     * @param  \App\Follow  $follow
     * @return void
     */
    public function restored(Follow $follow)
    {
        //
    }

    /**
     * Handle the follow "force deleted" event.
     *
     * @param  \App\Follow  $follow
     * @return void
     */
    public function forceDeleted(Follow $follow)
    {
        //
    }
}
