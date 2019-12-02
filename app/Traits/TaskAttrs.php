<?php

namespace App\Traits;

use App\Task;
use App\UserTask;
use Carbon\Carbon;

trait TaskAttrs
{
    //判断任务以完成状态
    public function getTaskStatusAttribute()
    {
        $status = 0;
        $user   = getUser();
        $type   = $this->type;

        if ($type == self::DAILY_TASK) {

            $usertask = UserTask::where([
                'task_id' => $this->id,
                'user_id' => $user->id,
            ])->whereDate('created_at', Carbon::today())->first();

            if (!$usertask) {
                $usertask = UserTask::firstOrCreate([
                    'task_id' => $this->id,
                    'user_id' => $user->id,
                ]);
            }
            $status = $usertask->status;
        }

        return $status;
    }

    public function getTaskProgressAttribute()
    {
        $user = getUser();

        $type = $this->type;
        $id   = $this->id;
        if ($type == self::DAILY_TASK) {
            if (!str_contains($this->name, 'All')) {
                $task_all = Task::where('name', $this->name . 'All')->first();
                if ($task_all) {
                    $id = $task_all->id;
                }
            }
        }

        return UserTask::where(['task_id' => $id, 'user_id' => $user->id])->whereDate('created_at', Carbon::today())->first()->progress;
    }

    public function getStartTimeAttribute()
    {
        return date("H:i", strtotime($this->start_at));
    }

    public function getEndTimeAttribute()
    {
        return date("H:i", strtotime($this->end_at));
    }
}
