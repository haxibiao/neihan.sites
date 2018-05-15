<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\article;
use App\Category;

class ArticleApproved extends Notification
{
    use Queueable;


    protected $article;
    protected $category;
    protected $approve_status;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Article $article, Category $category, $approve_status)
    {
        $this->article        = $article;
        $this->category           = $category;
        $this->approve_status = $approve_status;
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
            'type'    => 'other',
            'subtype' => 'article_approve',
            'message' => $this->category->link() . $this->approve_status . $this->article->link(),
        ];
    }
}
