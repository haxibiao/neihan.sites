<?php

namespace App\Observers;

use App\Feedback;
use Haxibiao\Task\Assignment;

class FeedbackObserver
{
    //
    public function updated(Feedback $feedback)
    {
        $user = $feedback->user;
        $commentTask = $user->tasks()->whereName('应用商店好评')->first();
        //更新好评任务的状态
        $assignment = $commentTask->assignments->where('user_id',$user->id)->first();
        $status = $feedback->status;
        if ($status==2){
            $assignment->status = Assignment::TASK_REACH;
        }else if ($status==1){
            $assignment->status = Assignment::TASK_DONE;
        }
        $assignment->save();
    }

}
