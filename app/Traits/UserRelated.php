<?php

namespace App\Traits;

use Auth;

trait UserRelated
{
    function isSelf()
    {
        return Auth::check() && Auth::id() == $this->user_id;
    }

    function isOfUser($user)
    {
        return $user && $user->id == $this->user_id;
    }
}
