<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReceiveAward extends Notification
{
    use Queueable;

    protected $subject;
    protected $gold;
    protected $sender;
    protected $article_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject,$gold,$sender,$article_id)
    {
        $this->subject = $subject;
        $this->gold    = $gold;
        $this->sender    = $sender;
        $this->article_id=$article_id;
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
            'type' => 'today_publish_post_receive_award',
            'subject' => $this->subject,
            'gold'    => $this->gold,
            'user_id' => $this->sender->id,
            'article_id' => $this->article_id,
        ];
    }
}
