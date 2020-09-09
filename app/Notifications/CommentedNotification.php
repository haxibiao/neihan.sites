<?php

namespace App\Notifications;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentedNotification extends Notification
{
    use Queueable;

    private $comment;
    private $sender;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->sender = $comment->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $isSelf = $notifiable->id == $this->sender->id;
        if($isSelf){
            return [];
        }
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'comment_id'        => $this->comment->id,
            'user_id'           => $this->comment->user_id,
            'id'                => $this->comment->commentable_id,
            'type'              => $this->comment->commentable_type,
        ];
    }
}
