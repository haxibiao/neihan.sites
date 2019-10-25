<?php

namespace App\Traits;

use App\Like;

trait CommentAttrs
{
    public function getRepliesAttribute()
    {
        return $this->replyComments()->latest('id')->take(20)->get();
    }

    public function getLikedAttribute()
    {
        if ($user = checkUser()) {
            $result = Like::ofType('comments')->ofUser($user->id)->where('liked_id', $this->id)->exists();
            if ($result) {
                return true;
            }
        }
        return false;
    }
}
