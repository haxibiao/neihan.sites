<?php

namespace App\Listeners;

use App\Events\NewLike;
use App\Like;
use App\Notifications\ArticleLiked;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLikeNotification implements ShouldQueue
{

    public $delay = 60 * 10;

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

        $like = $event->like;

        //排除重复点赞发送通知
        $is_deLike = Like::onlyTrashed()->where([
            'user_id'    => $like->user_id,
            'liked_type' => $like->liked_type,
            'liked_id'   => $like->liked_id,
        ])->exists();

        if ($like->liked instanceof \App\Article) {
            $article = $like->liked;
            if (!$is_deLike && $like->user && $article->user && $article->user->id != $like->user->id) {
                $article->user->notify(new ArticleLiked($article->id, $like->user->id));
            }
        }
    }
}
