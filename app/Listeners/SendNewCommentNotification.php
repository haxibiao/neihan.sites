<?php

namespace App\Listeners;

use App\Events\NewComment;
use App\Notifications\ArticleCommented;
use App\Notifications\CommentedNotification;
use App\Notifications\ReplyComment;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewCommentNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public $delay = 10;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewComment  $event
     * @return void
     */
    public function handle(NewComment $event)
    {
        $comment = $event->comment;
        //TODO: 可以对很多新的评论的时候，合并一堆的评论来聚合法一个通知，比如一个发布，一段时间汇总总评论数
        if ($comment->commentable instanceof \App\Article) {
            $article = $comment->commentable;
            // 发送通知，如果是作者本人就不发
            if ($comment->user && $article->user && $comment->user->id != $article->user->id) {
                //TODO: 即时发送每个通知，需要改为汇总到 Listener里去决策
                $article->user->notify(new ArticleCommented($comment));
            }
        } else if ($comment->commentable instanceof \App\Comment) {
            $user = $comment->commentable->user;
            $user->notify(new CommentedNotification($comment));
            //TODO: 即时发送每个通知，需要改为汇总到 Listener里去决策
//            if ($comment->commentable->user) {
//                $comment->commentable->user->notify(new ReplyComment($comment));
//            }
        } else if($comment->commentable instanceof \App\Post){
            $post = $comment->commentable;
            $user = $post->user;
            $user->notify(new CommentedNotification($comment));
        }

    }
}
