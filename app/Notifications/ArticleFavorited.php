<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Article;

class ArticleFavorited extends Notification {
	use Queueable; 

	protected $article;
	protected $user;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(Article $article, User $user) {
		$this->article = $article;
		$this->user    = $user; 
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
	public function toArray($notifiable) {
		return [ 
			'type' => 'other',
			'user_avatar' 	=> $this->user->avatar,
			'user_name' 	=> $this->user->name,
			'user_id' 		=> $this->user->id,
			'article_title' => $this->article->title, 
			'article_id' 	=> $this->article->id,
			'message' 		=> $this->user->link() . "收藏了您的文章" . $this->article->link(),
		];
	}
}
