<?php

namespace App\Observers;

use App\Gold;

class GoldObserver
{
    public function created(Gold $gold)
    {
        // $user = $gold->user;
        // \DDZUser::updateGold($user, $gold);
    }
}
