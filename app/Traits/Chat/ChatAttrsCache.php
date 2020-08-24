<?php

namespace App\Traits;

use App\Notification;
use App\User;

trait ChatAttrsCache
{
    public function getUnreadsCache()
    {
        return $this->pivot->unreads;
    }

    public function getUpdatedAtAttribute($value)
    {
        return time_ago($value);
    }

    public function getWithUserCache()
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

    public function getLastMessageCache()
    {
        $messageModel = $this->messages()->latest('id')->first();
        return $messageModel ? $messageModel : null;
    }

    public function getClearUnreadCache()
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
