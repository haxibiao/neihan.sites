<?php

namespace App\Notifications;

use App\Question;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionAnswered extends Notification
{
    use Queueable;

    protected $user;
    protected $question;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $question_id)
    {
        $this->user     = User::find($user_id);
        $this->question = Question::find($question_id);
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
            'subtype' => 'question_answered',
            'message' => $this->user->link() . '回答了您的问题' . $this->question->link(),
        ];
    }
}
