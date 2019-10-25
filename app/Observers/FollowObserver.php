<?php

namespace App\Observers;

use App\Follow;
use App\Notifications\UserFollowed;

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
        //同步用户关注数
        $user                            = $follow->user;
        $user->profile->count_followings = $user->followings()->count();
        $user->profile->save();

        //同步被关注着的粉丝数
        $count = Follow::where('followed_type', $follow->followed_type)->where("followed_id", $follow->followed_id)->count();
        $follow->followed->profile->update(['count_follows' => $count]);

        //如果关注的是用户则发送消息通知给用户 ,只能存在这一条信息
        if ($follow->followed instanceof \App\User) {
            //TODO: 即时发送每个通知，需要改为汇总到 Listener里去决策 然后发送通知（job）
            $follow->followed->notify(new UserFollowed($user));
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
        $follow->followed->profile->update(['count_follows' => $count]);
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
