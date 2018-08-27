<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Article;
use App\Tip;

class ArticleTiped extends Notification
{
    use Queueable;
    protected $article;
    protected $user;
    protected $tip;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Article $article, User $user, Tip $tip)
    {
        $this->article = $article;
        $this->user = $user;
        $this->tip = $tip;
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
        $article_title = $this->article->title?:$this->article->video->title;
        // 标题 视频标题都不存在 则取description
        if(empty($this->article->title)){
            $article_title = $this->article->get_description();
        }
        return [
            'type' => 'tip',
            'amount' => $this->tip->amount,
            'tip_id' => $this->tip->id,
            'message' => $this->tip->message,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'user_id' => $this->user->id,
            'article_title' => $article_title,
            'article_id' => $this->article->id,
        ];
    }
}
