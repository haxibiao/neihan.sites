<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Category;
use App\Article;
/**
 * 作用:投稿请求的通知类型
 * 注意这个通知类型已经被弃用了
 */
class CategoryRequested extends Notification
{
    use Queueable;

    protected $category;
    protected $article;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Category $category, Article $article)
    {
        $this->category = $category;
        $this->article = $article;
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
            'type' => 'category_request',
            'category_id' => $this->category->id,            
            'user_id' => $this->article->user->id,            
            'user_name' => $this->article->user->name,
            'user_avatar' => $this->article->user->avatar,
            'article_id' => $this->article->id,            
            'article_title' => $this->article->title,
            'article_description' => $this->article->description,
            'article_image_url' => $this->article->image_url,
            'article_hits' => $this->article->hits,
            'article_count_replies' => $this->article->count_replies,
            'article_count_likes' => $this->article->count_likes,
            'article_count_tips' => $this->article->count_tips,
        ];
    }
}
