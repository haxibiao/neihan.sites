<?php

namespace App\Listeners;

use App\Events\NewLike;
use App\Like;
use App\Notifications\ArticleLiked;
use App\Notifications\LikedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotification implements ShouldQueue
{

    public $delay =  10;

    public function __construct()
    {
        //
    }

    /**
     * ivan(2019-10-15): 监听喜欢点赞操作的其他响应.
     * LikeObserver 应该已经完成了基本的数据更新触发
     * 这里可以用来实现一些复杂的 job逻辑，比如 延迟发送通知，聚合发送点赞通知 ...
     * @param  NewLike  $event
     * @return void
     */
    public function handle(NewLike $event)
    {

        $this->like = $event->like;
        $likable    = $this->like->likable;

        if (!is_null($likable)) {
            $likableUser = $likable->user;
            if (!is_null($likableUser)) {
                $likable->user->notify(new LikedNotification($this->like));
            }
        }
    }
}
