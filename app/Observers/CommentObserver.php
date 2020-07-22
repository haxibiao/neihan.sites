<?php

namespace App\Observers;

use App\Ip;
use App\Action;
use App\comment;
use App\Contribute;
use App\Events\NewComment;
use Illuminate\Support\Facades\DB;

class CommentObserver
{

    public function creating(comment $comment)
    {
        // nova create with user id
        if (empty($comment->user_id)) {
            $comment->user_id = Auth()->id();
        }
    }

    public function created(comment $comment)
    {
        if ($comment->user->isBlack()) {
            // $article->delete();
            $comment->status = -1;
            $comment->save();
            // throw new GQLException('发布失败,你以被禁言');

        }
        event(new NewComment($comment));
        if ($comment->commentable instanceof \App\Article) {
            $article = $comment->commentable;
            $article->count_replies = $article->count_replies + 1;
            $article->count_comments = $article->comments()->whereNull('comment_id')->count();
            $article->save();
            $comment->lou = $article->count_comments;
            $comment->save();
        }

        //评论任务
        if (!is_null($comment->commentable->user)) {
            $profile = $comment->commentable->user->profile;
            // 奖励贡献值
            if ($comment->user->id != $comment->commentable->user->id) {
                //新增任务：评论奖励金币
                $user = $comment->user;
                //刷新“点赞超人”任务进度
                \App\Task::refreshTask($user, "评论高手");

                //不奖励贡献值了，直接做任务，任务激励
                // $profile->increment('count_contributes', Contribute::COMMENTED_AMOUNT);
            }
        }

        Action::createAction('comments', $comment->id, $comment->user->id);
        Ip::createIpRecord('comments', $comment->id, $comment->user->id);
    }
}
