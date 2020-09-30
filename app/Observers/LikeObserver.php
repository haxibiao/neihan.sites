<?php

namespace App\Observers;

use App\Collection;
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

        //检查点赞任务是否完成了
        $user->reviewTasksByClass(get_class($like));

        if ($like->likable instanceof \App\Collection) {
            if ($like->likable->name == "一起看阅兵") {
                \App\Task::refreshTask($user, "国庆活动");
            } else if ($like->likable->name == "中秋故事汇") {
                \App\Task::refreshTask($user, "中秋活动");
            }
        }

        app_track_event('用户', '点赞');
        event(new NewLike($like));
    }

    public function deleted(Like $like)
    {
        $user                       = $like->user;
        $user->profile->count_likes = $user->likes()->count();
        $user->profile->save();
    }
}
