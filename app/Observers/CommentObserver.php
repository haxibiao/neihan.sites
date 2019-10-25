<?php

namespace App\Observers;

use App\comment;
use App\Events\NewComment;

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
        event(new NewComment($comment));

        if ($comment->commentable instanceof \App\Article) {
            $article                 = $comment->commentable;
            $article->count_replies  = $article->comments()->count(); //FIXME: when comments go big ...
            $article->count_comments = $article->comments()->whereNull('comment_id')->count();
            $article->save();
            $comment->lou = $article->count_comments;
            $comment->save();
        }
    }

}
