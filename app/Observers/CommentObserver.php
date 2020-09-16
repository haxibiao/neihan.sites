<?php

namespace App\Observers;

use App\Action;
use App\comment;
use App\Contribute;
use App\Events\NewComment;
use App\Ip;

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
            $article                 = $comment->commentable;
            $article->count_replies  = $article->count_replies + 1;
            $article->count_comments = $article->comments()->whereNull('comment_id')->count();
            $article->save();
            $comment->lou = $article->count_comments;
            $comment->save();
        }
        if ($comment->commentable instanceof \App\Post) {
            $posts                 = $comment->commentable;
            $posts->count_comments = $posts->comments()->whereNull('comment_id')->count();
            $posts->save();
            $comment->lou = $posts->count_comments;
            $comment->save();
        }
        $profile = $comment->commentable->user->profile;
        // 奖励贡献值
        if ($comment->user->id != $comment->commentable->user->id) {
            $profile->increment('count_contributes', Contribute::COMMENTED_AMOUNT);
        }
        Action::createAction('comments', $comment->id, $comment->user->id);
        Ip::createIpRecord('comments', $comment->id, $comment->user->id);
    }

    public function deleted(comment $comment)
    {
        if ($comment->commentable instanceof \App\Article) {
            $article                 = $comment->commentable;
            $article->count_replies  = $article->count_replies - 1;
            $article->count_comments = $article->comments()->whereNull('comment_id')->count();
            $article->save();
            $comment->lou = $article->count_comments;
            $comment->save();
        }
        if ($comment->commentable instanceof \App\Post) {
            $posts                 = $comment->commentable;
            $posts->count_comments = $posts->comments()->whereNull('comment_id')->count();
            $posts->save();
            $comment->lou = $posts->count_comments;
            $comment->save();
        }
        $profile = $comment->commentable->user->profile;
        // 奖励贡献值
        if ($comment->user->id != $comment->commentable->user->id) {
            $profile->decrement('count_contributes', Contribute::COMMENTED_AMOUNT);
        }
    }
}
