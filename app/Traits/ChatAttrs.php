<?php

namespace App\Traits;

use App\Notification;
use App\User;

trait ChatAttrs
{
    public function getUnreadsAttribute()
    {
        return $this->pivot->unreads;
    }

    public function getWithUserAttribute()
    {
        if ($user = getUser()) {
            $uids        = json_decode($this->uids);
            $current_uid = $user->id;
            $with_id     = array_sum($uids) - $current_uid;
            $with        = User::find($with_id);
        }
        //确保聊天对象不为空，有问题的时候，消息发送给user id 1
        return $with ?? User::find(1);
    }

    public function getLastMessageAttribute()
    {
        $messageModel = $this->messages()->latest('id')->first();
        return $messageModel ? $messageModel->message : '';
    }

    public function getClearUnreadAttribute()
    {
        if ($user = checkUser()) {
            $unread_notifications = Notification::where([
                'type'          => 'App\Notifications\ChatNewMessage',
                'notifiable_id' => $user->id,
                'read_at'       => null,
            ])->get();
            foreach ($unread_notifications as $notify) {
                $notify->read_at = now();
                $notify->save();
            }
            return true;
        }
        return false;
    }
}
