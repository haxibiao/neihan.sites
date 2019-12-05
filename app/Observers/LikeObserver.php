<?php

namespace App\Observers;

use App\Contribute;
use App\Events\NewLike;
use App\Like;

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
            $article              = $like->liked;
            $article->count_likes = $article->likes()->count();
            $article->save();
        } else if ($like->liked instanceof \App\Comment) {
            $comment              = $like->liked;
            $comment->count_likes = $comment->likes()->count();
            $comment->save();

            //TODO: 评论被点赞的通知，暂时不发
        }

        $user                 = $like->user;
        $profile              = $user->profile;
        $profile->count_likes = $user->likes()->count();
        $profile->save();
        if ($user->id != $like->liked->user->id) {
            $like->liked->user->profile->increment('count_contributes', Contribute::LIKED_AMOUNT);
        }

        event(new NewLike($like));

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
            $article              = $like->liked;
            $article->count_likes = $article->likes()->count();
            $article->save();
        } else if ($like->liked instanceof \App\Comment) {
            $comment              = $like->liked;
            $comment->count_likes = $comment->likes()->count();
            $comment->save();
        }
        $user                       = $like->user;
        $user->profile->count_likes = $user->likes()->count();
        $user->profile->save();
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
