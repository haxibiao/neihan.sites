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
        $user     = getUser();
        $type     = $this->type;
        $usertask = UserTask::where([
            'task_id' => $this->id,
            'user_id' => $user->id,
        ]);
        $status = empty($usertask->first()) ? null : $usertask->first()->status;
        $status = $this->checkTaskStatus($user);

        if ($type == self::TIME_TASK) {
            $date_user_task = $usertask->whereDate('created_at', Carbon::today())->first();
            if ($date_user_task) {
                $status = $date_user_task->status;

            }
        }

        return $status;
    }

    public function getTaskProgressAttribute()
    {
        $user = getUser();

        $type = $this->type;
        if ($type == self::TIME_TASK) {
            if (!str_contains($this->name, 'All')) {
                $task_all = Task::where('name', $this->name . 'All')->first();
                return $task_all->getUserTask($user->id)->progress;
            }
            return $this->getUserTask($user->id)->progress;
        }

        $user_task = $this->getUserTask($user->id);
        $progress  = !empty($user_task) ? $user_task->progress : null;
        return $progress;
    }

    public function getProgressDetailsAttribute()
    {
        $taskCount      = null;
        $method         = $this->resolve['method'] ?? null;
        $progressMethod = !empty($method) ? $this->resolve['method'] . 'Count' : null;
        if (!is_null($progressMethod) && method_exists($this, $progressMethod)) {
            $argsMethod = $progressMethod . 'Args';
            $args       = (!is_null($argsMethod) && method_exists($this, $argsMethod)) ? $this->$argsMethod() : [];
            try {
                $taskCount = $this->$progressMethod(...$args);
            } catch (Exception $e) {
                \info($e->getMessage());
            }
        }
        $limit           = $this->resolve['limit'] ?? null;
        $progress_detail = (!empty($progressMethod) && !empty($limit)) ? sprintf('%d / %d', $taskCount, $limit) : $this->submit_name;

        return $progress_detail;
    }

    public function getTaskInfoAttribute()
    {
        return $this->resolve;
    }

    public function getRewardInfoAttribute()
    {
        $json = $this->reward;

        $gold       = array_get($json, "gold");
        $contribute = array_get($json, "contribute");

        if (empty($gold)) {
            $goldmin = array_get($json, "mingold");
            $goldmax = array_get($json, "maxgold");

            if (!empty($goldmin) && !empty($goldmax)) {
                $gold = random_int($goldmin, $goldmax);
            }
        }
        $data = [
            'gold'       => $gold ?? null,
            'contribute' => $contribute,
        ];

        return $data;
    }

    public function getSubmitNameAttribute()
    {
        if ($this->type == self::CUSTOM_TASK) {
                // $submit_name = $this->resolve['submit_name'] ?? null;
                // if (is_null($submit_name)) {
                //     return $this->resolve['submit_name'];
                // }
            return '进入';
        }

        if (is_null($this->task_status)) {
            return '领取任务';
        }

        switch ($this->task_status) {
            case UserTask::TASK_UNDONE:
                return '未完成';
                break;
            case UserTask::TASK_REACH:
                return '领取奖励';
                break;
            case UserTask::TASK_DONE:
                return '完成';
                break;

        }

    }

    public function getTaskClassAttribute()
    {
        switch ($this->type) {
            case self::NEW_USER_TASK:
                return '新人任务';
            case self::DAILY_TASK:
                return '每日任务';
            case self::CUSTOM_TASK:
                return '自定义任务';
        }
    }

    public function getSleepStatusAttribute()
    {
        switch ($this->name) {
            case "SleepMorning":
                return true;
                break;
            case "SleepNight":
                return false;
                break;
        }
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
