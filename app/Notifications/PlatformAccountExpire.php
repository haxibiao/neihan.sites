<?php

namespace App\Notifications;

use App\PlatformAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlatformAccountExpire extends Notification implements ShouldQueue
{
    use Queueable;

    protected $platformAccount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PlatformAccount $platformAccount)
    {
        $this->platformAccount = $platformAccount;
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
        $platformAccount = $this->platformAccount;

        // $seconds = now()->diffInSeconds(Carbon::parse($order->created_at)) < $lengthOfTime;
        return [
            'user_id' => $platformAccount->user_id,
            'product_id' => $platformAccount->product_id,
            'order_id' => $platformAccount->order_id,
            'order_status' => $platformAccount->order_status,
            'platform' => $platformAccount->platform,
            'account' => $platformAccount->account,
            'password' => $platformAccount->password,
        ];
    }
}
