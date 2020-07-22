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
            if (!$author) {
                return null;
            }
            $tasks = $author->like_tasks;
            $user  = $like->article->user; //获取的是 收到点赞的用户
            $this->updateProfileCountLikes($user);
            //刷新“点赞超人”任务进度
            \App\Task::refreshTask($user, "点赞超人");

            foreach ($tasks as $task) {
                $task->checkTaskStatus($author);
            }
        } else if ($like->likable instanceof \App\Comment) {
            $comment              = $like->likable;
            $comment->count_likes = $comment->likes()->count();
            $comment->save();

            $user = $like->comment->user; //获取的是 收到点赞的用户
            $this->updateProfileCountLikes($user);
        }

        //TODO: 评论被点赞的通知，暂时不发

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

    public function updateProfileCountLikes($user)
    {

        //保存总喜欢数量
        $profile = $user->profile;
        //TODO: 需要修复一个用户所有可like的东西(article, comment)的 count_likes sum起来，然后每次新like ++
        $profile->count_likes = ++$profile->count_likes;
        $profile->save();

        //保存每日喜欢数量，用于获赞任务
        if (!$user->tasks()->whereName('作品获赞')->first()) {
            return;
        }
        $assignments                = $user->tasks()->whereName('作品获赞')->first()->pivot;
        $assignments->current_count = ++$assignments->current_count;

        $assignments->save();
        return $profile;
    }
}
