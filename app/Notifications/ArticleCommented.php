<?php

namespace App\Notifications;

use App\Article;
use App\Comment;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArticleCommented extends Notification {

	protected $article;
	protected $user;
	protected $comment;
	protected $title;
	protected $body;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(Article $article, Comment $comment, User $user, String $title = '', String $body = '') {
		$this->article = $article;
		$this->user = $user;
		$this->comment = $comment;
		$this->title = $title;
		$this->body = $body;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable) {
		return ['database'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		$article = $this->$article;
		$url = '/article/' . $article->id;
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
	public function toArray($notifiable) {
		if (empty($this->body)) {
			$this->body = $this->comment->body;
		}
		return [
			'type' => 'comment',
			'user_id' => $this->user->id,
			'user_avatar' => $this->user->avatar,
			'user_name' => $this->user->name,
			'article_title' => $this->article->get_title(),
			'article_id' => $this->article->id,
			'comment_id' => $this->comment->id,
			'comment' => $this->comment->body,
			'title' => $this->title,
			'body' => $this->body,
			'url' => $this->article->content_url(),
			'lou' => $this->comment->lou,
		];
	}
}
