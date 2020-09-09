<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $user->reviewTasksByClass(get_class($user));
    }

}
