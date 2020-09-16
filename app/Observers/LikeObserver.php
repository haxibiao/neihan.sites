<?php

namespace App\Observers;

use App\Contribute;
use App\Events\NewLike;
use App\Like;

class LikeObserver
{
    public function created(Like $like)
    {
        $user                 = $like->user;
        $profile              = $user->profile;
        $profile->count_likes = $user->likes()->count();
        $profile->save();
        app_track_event('用户','点赞');
        event(new NewLike($like));

    }

    public function deleted(Like $like)
    {
        $user                       = $like->user;
        $user->profile->count_likes = $user->likes()->count();
        $user->profile->save();
    }
}
