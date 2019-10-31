<?php

namespace App\Notifications;

use App\Article;
use App\Comment;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleCommented extends Notification
{

    protected $article;
    protected $user;
    protected $comment;
    protected $title;
    protected $body;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;

        $this->article = $comment->commentable;
        $this->user    = $comment->user;
        $this->title   = $comment->commentable->title;
        $this->body    = $comment->commentable->body;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $mailSubject = $this->user->name . ', 您的文章收到了新的评论.';
        return (new MailMessage)
            ->from('notification@' . env('APP_DOMAIN'), config('app.name_cn'))
            ->subject($mailSubject)
            ->line($mailSubject)
            ->action('回复他', $this->article->url)
            ->line($this->user->name . ' 在您的文章 ' . $this->title . ' 下写道： ' . str_limit($this->comment->body));
    }

    public function toArray($notifiable)
    {
        if (empty($this->body)) {
            $this->body = $this->comment->body;
        }
        return [
            'type'          => 'comment',
            'user_id'       => $this->user->id,
            'user_avatar'   => $this->user->avatarUrl,
            'user_name'     => $this->user->name,
            'article_title' => $this->article->title,
            'article_id'    => $this->article->id,
            'comment_id'    => $this->comment->id,
            'comment'       => $this->comment->body,
            'title'         => $this->title,
            'body'          => $this->body,
            'url'           => $this->article->url,
            'lou'           => $this->comment->lou,
        ];
    }
}
