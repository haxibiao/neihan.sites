<?php

namespace App\Notifications;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReplyComment extends Notification
{
    use Queueable;

    protected $reply;

    public function __construct(Comment $comment)
    {
        $this->reply = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $user    = $this->reply->user;
        $comment = $this->reply->commentable;
        return [
            'user_id'       => $user->id,
            'user_avatar'   => $user->avatarUrl,
            'user_name'     => $user->name,
            'reply_content' => $this->reply->getContent(),
            'reply_id'      => $this->reply->id,
            'comment_id'    => $comment->id,
            'comment_body'  => $comment->body,
        ];
    }
}
