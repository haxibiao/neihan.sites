<?php

namespace App\Notifications;

use App\Article;
use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleCommented extends Notification implements ShouldQueue
{
    use Queueable;

    protected $article;
    protected $user;
    protected $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Article $article, Comment $comment, User $user)
    {
        $this->article = $article;
        $this->user    = $user;
        $this->comment = $comment;
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
        $article = $this->$article;
        $url     = '/article/' . $article->id;
        return (new MailMessage)
            ->line('您的文章收到了新的评论.')
            ->action('回复他', url($url))
            ->line('××用户，在您的文章××× 下写道： ' . str_limit($this->comment->body));
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
            'comment_id'    => $this->comment->id,
            'comment'       => $this->comment->body,
        ];
    }
}
