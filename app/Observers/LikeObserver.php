<?php

namespace App\Observers;

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

        if ($like->likable instanceof \App\Article) {
            $article              = $like->likable;
            $article->count_likes = $article->likes()->count();
            $article->save();

            //更新用户在文章被点赞任务方面,作者的各任务的指派的状态
            $author = $article->user;
            $tasks  = $author->like_tasks;

            foreach ($tasks as $task) {
                $task->checkTaskStatus($author);
            }
        } else if ($like->likable instanceof \App\Comment) {
            $comment              = $like->likable;
            $comment->count_likes = $comment->likes()->count();
            $comment->save();

            //TODO: 评论被点赞的通知，暂时不发
        }

        //用户收到新的点赞...
        $user    = $like->user;
        $profile = $user->profile;
        //TODO: 需要修复一个用户所有可like的东西(article, comment)的 count_likes sum起来，然后每次新like ++
        $profile->count_likes = ++$profile->count_likes;
        $profile->save();

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
        if ($like->likable instanceof \App\Article) {
            $article              = $like->likable;
            $article->count_likes = $article->likes()->count();
            $article->save();
        } else if ($like->likable instanceof \App\Comment) {
            $comment              = $like->likable;
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
