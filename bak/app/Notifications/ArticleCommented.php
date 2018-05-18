<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Article;
use App\User;

class ArticleCommented extends Notification implements ShouldQueue
{
    use Queueable;

    protected $article;
    protected $user;
    protected $comment;
    protected $lou;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($article_id, $user_id, $comment, $lou)
    {
        $this->article = Article::find($article_id);
        $this->user    = User::find($user_id);
        $this->comment = $comment;
        $this->lou     = $lou;
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
            'type'          => 'comment',
            'user_id'       => $this->user->id,
            'user_avatar'   => $this->user->avatar,
            'user_name'     => $this->user->name,
            'article_title' => $this->article->title,
            'article_id'    => $this->article->id,
            'lou'           => $this->lou,
            'comment'       => $this->comment,
        ];
    }
}
