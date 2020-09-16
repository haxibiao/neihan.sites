<?php

namespace App\Traits;

use App\UserTask;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
            $dateUserTask = $usertask->whereDate('updated_at', Carbon::today())->first();
            if ($dateUserTask) {
                $status = $dateUserTask->status;
            }
        }

        return $status;
    }

    public function getTaskProgressAttribute()
    {
        $user = getUser();
        //TODO ::time_task进度只获取自己的,不从其他地方获取了
        // if ($type == self::TIME_TASK) {
        //     if (!str_contains($this->name, 'All')) {
        //         $task_all = Task::where('name', $this->name . 'All')->first();
        //         return $task_all->getUserTask($user->id)->progress;
        //     }
        //     return $this->getUserTask($user->id)->progress;
        // }

        $user_task = $this->getUserTask($user->id);
        $progress  = !empty($user_task) ? $user_task->progress : null;
        //父任务进度

        if ($this->isparentTasks()) {
            $progress = $this->getUserTask($user->id, true) ? $this->getparentTaskProgress($user->id) : 0;
        }

        if ($this->ischildrenTasks()) {
            $progress = $this->parentTasks->getUserTask($user->id, true) ? $this->parentTasks->getparentTaskProgress($user->id) : 0;
        }

        return $progress;
    }

    public function getProgressDetailsAttribute()
    {
        if ($this->type === 1) {
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
            $progress_detail = (!empty($progressMethod) || !empty($limit)) ? sprintf('%d / %d', $taskCount, $limit) : $this->submit_name;

            return $progress_detail;
        }
        return null;
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
            switch ($this->task_status) {
                case UserTask::TASK_REVIEW:
                    return '审核中';
                    break;
                case UserTask::TASK_REACH:
                    return '领奖';
                    break;
                case UserTask::TASK_DONE:
                    return '完成';
                    break;
            }
            return '进入';
        }

        if (is_null($this->task_status)) {
            return '领取';
        }

        switch ($this->task_status) {
            case UserTask::TASK_FAILED:
                return Arr::get($this->resolve, 'task_failed') ?? '失败';
                break;
            case UserTask::TASK_UNDONE:
                return Arr::get($this->resolve, 'task_undone') ?? '未完成';
                break;
            case UserTask::TASK_REVIEW:
                return Arr::get($this->resolve, 'task_review') ?? '进行中';
                break;
            case UserTask::TASK_REACH:
                return Arr::get($this->resolve, 'task_reach') ?? '领奖';
                break;
            case UserTask::TASK_DONE:
                return Arr::get($this->resolve, 'task_done') ?? '完成';
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
        switch (Arr::get($this->resolve, 'task_en')) {
            case "Wake":
                return true;
                break;
            case "Sleep":
                return false;
                break;
            default:
                return true;
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
