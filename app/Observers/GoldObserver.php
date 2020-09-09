<?php

namespace App\Observers;

use App\Gold;

class GoldObserver
{
    public function created(Gold $gold)
    {
        //更新user表上的冗余字段
        $user = $gold->user;
        $user->update(['gold' => $gold->balance]);
        //更新任务状态
        $user->reviewTasksByClass(get_class($gold));

    }
}
