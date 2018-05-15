<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Question;
use App\User;

class QuestionBonused extends Notification
{
    use Queueable;

    protected $user;
    protected $question;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Question $question)
    {
        $this->user = $user;
        $this->question = $question;
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
            'subtype' => 'question_bonused',
            'question_id' => $this->question->id,
            'message' => $this->user->link() . '奖励了您回答的问题' . $this->question->link(),
        ];
    }
}
