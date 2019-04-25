<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->delay(now())->subMinute(5); //冷静5分钟后没关注了，就不发消息
    }

    public function via($notifiable)
    {
        if ($this->dontSend($notifiable)) {
            return [];
        }
        return ['mail'];
    }

    public function dontSend($notifiable)
    {
        return !$this->user->isFollow("users", getUserId());
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'type'        => 'follow',
            'user_avatar' => $this->user->avatar,
            'user_name'   => $this->user->name,
            'user_id'     => $this->user->id,
        ];
    }
}
