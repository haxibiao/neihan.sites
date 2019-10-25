<?php

namespace App\Observers;

use App\Like;
use App\Notifications\ArticleLiked;

class LikeObserver
{
    /**
     * Handle the like "created" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        if ($like->liked instanceof \App\Article) {
            $article = $like->liked;
            $article->count_likes += 1;
            $article->save();
            if ($article->user->id != $like->user->id) {
                //TODO: 即时发送每个通知，需要改为汇总到 Listener里去决策
                $article->user->notify(new ArticleLiked($article->id, $like->user->id));
            }
        } else if ($like->liked instanceof \App\Comment) {
            $comment = $like->liked;
            $comment->likes += 1;
            $comment->save();
            //TODO: 评论被点赞的通知，暂时不发
        } else if ($like->liked instanceof \App\User) {
            $user = $like->liked;
            $user->count_likes += 1;
            $user->save();
        }
        // event(new NewLike($like)); //统一只在observer到的created event 触发event
    }

    /**
     * Handle the like "updated" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function updated(Like $like)
    {
        //
    }

    /**
     * Handle the like "deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        if ($like->liked instanceof \App\Article) {
            $article = $like->liked;
            $article->count_likes -= 1;
            $article->save();
        } else if ($like->liked instanceof \App\Comment) {
            $comment = $like->liked;
            $comment->likes -= 1;
            $comment->save();
        } else if ($like->liked instanceof \App\User) {
            $user = $like->liked;
            $user->count_likes += 1;
            $user->save();
        }
    }

    /**
     * Handle the like "restored" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function restored(Like $like)
    {
        //
    }

    /**
     * Handle the like "force deleted" event.
     *
     * @param  \App\Like  $like
     * @return void
     */
    public function forceDeleted(Like $like)
    {
        //
    }
}
