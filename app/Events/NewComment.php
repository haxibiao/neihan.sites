<?php

namespace App\Events;

use App\Article;
use App\Comment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public $commentable;

    public function __construct(Comment $comment)
    {
        $this->comment     = $comment;
        $this->commentable = $comment->commentable;
    }

    public function broadcastOn()
    {
        if (isset($this->commentable->user_id)) {
            return new PrivateChannel('App.User.' . $this->commentable->user_id);
        }
    }

    public function broadcastWith()
    {

        $user = $this->comment->user;

        if ($this->commentable instanceof Article) {

            $moudle  = '动态';
            $content = str_limit(strip_tags($this->comment->body), 3);

        } else if ($this->commentable instanceof Comment) {
            $moudle  = '评论';
            $content = str_limit(strip_tags($this->comment->body), 3);
        }

        $content = sprintf('%s 刚刚评论了你的%s《%s》', $this->comment->user->name, $moudle, $content);
        $data    = [
            'title'           => '新评论提醒',
            'comment_content' => $content,
            'comment_id'      => $this->comment->id,
            'commentable_id'  => $this->commentable->id,
            'user_id'         => $user->id,
            'user_avatar'     => $user->avatar,
            'user_name'       => $user->name,
        ];

        return $data;
    }
}
