<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;
    protected $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment,User $sender)
    {
        $this->comment = $comment;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type'    => 'comment',
            'comment_id' => $this->comment->id,
            'article_id' => $this->comment->commentable_id,
            'user_id'   => $this->sender->id,
        ];
    }
}
