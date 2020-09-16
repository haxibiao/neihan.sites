<?php

namespace App\Notifications;

use App\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LikedNotification extends Notification
{
    use Queueable;

    protected $like;
    protected $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Like $like)
    {
        $this->like = $like;
        $this->sender = $like->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $isSelf = $notifiable->id == $this->sender->id;
        if($isSelf){
            return [];
        }
        $notification = $notifiable->notifications()
            ->whereType('App\Notifications\LikedNotification')
            ->where('data->type',$this->like->likable_type)
            ->where('data->id',$this->like->likable_id)
            ->where('data->user_id',$this->like->user_id)
            ->first();
        if($notification){
            $notification->data = [
                'like_id'   => $this->like->id,
                'id'    => $this->like->likable_id,
                'type'  => $this->like->likable_type,
                'user_id'   => $this->like->user_id,
            ];
            $notification->save();
            return [];
        }
        return ['database'];
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
            'like_id'   => $this->like->id,
            'user_id'   => $this->like->user_id,
            'id'    => $this->like->likable_id,
            'type'  => $this->like->likable_type,
        ];
    }
}
