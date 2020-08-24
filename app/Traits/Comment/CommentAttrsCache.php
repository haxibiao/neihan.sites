<?php

namespace App\Traits;

use App\Like;

trait CommentAttrsCache
{
    public function getRepliesCache()
    {
        return $this->replyComments()->latest('id')->take(20)->get();
    }

    public function getLikedCache()
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
