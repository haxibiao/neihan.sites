<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CommentAccepted extends Notification
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
//        if ($notifiable->id === $this->sender->id) {
//            return [];
//        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'type'    => 'comment_accpeted',
            'comment_id' => $this->comment->id,
            'article_id' => $this->comment->commentable_id,
            'user_id'   => $this->sender->id,
        ];
    }
}
