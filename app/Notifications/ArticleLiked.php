<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Article;
use App\User;
/**
 * 问答，文章，视频，动态的评论全都由本类负责通知
 */
class ArticleLiked extends Notification implements ShouldQueue
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
    public function __construct($article_id, $user_id, $comment = null)
    {
        $this->article  = Article::find($article_id);
        $this->user     = User::find($user_id);
        $this->comment  = $comment; 
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
        if( !empty($this->comment) ){
            $body       = '赞你的评论';
            //评论文章，视频，答案，动态
            $lou            = $this->comment->lou;
            $comment_body   = $this->comment->body;
            $commentable    = $this->comment->commentable;
            //评论问答中的答案
            if($this->comment->commentable_type == 'answers'){
                $question = $commentable->question;
                $url    = '/question/' . $question->id;   //TODO 锚点信息需要与前端沟通
                $title  = $comment_body;
            //其余的都是article的子类型
            } else {
                $url    = $commentable->content_url() . '#'. $lou;
                $title  = $comment_body;
            }
        } else {
            $body   = '喜欢了你的' . $this->article->resoureTypeCN();
            $url    = $this->article->content_url();
            $title  = '《' . $this->article->title . '》';
        }
        return [
            'type' => 'like',
            'user_avatar'   => $this->user->avatar,
            'user_name'     => $this->user->name,
            'user_id'       => $this->user->id,
            'article_title' => $this->article->title,
            'article_id'    => $this->article->id,
            'url'           => $url,
            'body'          => $body,
            'title'         => $title 
        ];
    }
}
