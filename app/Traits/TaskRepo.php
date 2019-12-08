<?php

namespace App\Traits;

use App\Action;
use App\Task;
use App\User;
use App\UserTask;
use Carbon\Carbon;

trait TaskRepo
{
    public function isDailyTask()
    {
        return $this->type == self::DAILY_TASK;
    }

    public function getDailyStartTime()
    {
        $start_at       = $this->start_at;
        $start_date     = Carbon::parse($start_at);
        $start_date_day = $start_date->addDays($start_date->diffInDays(Carbon::tomorrow()));

        if (empty($start_at)) {
            $start_date_day = Carbon::today();
        }
        return $start_date_day;
    }

    public function getDailyEndTime()
    {
        $end_at       = $this->end_at;
        $end_date     = Carbon::parse($end_at);
        $end_date_day = $end_date->addDays($end_date->diffInDays(Carbon::tomorrow()));

        if (empty($end_at)) {
            $end_date_day = Carbon::tomorrow();
        }

        return $end_date_day;
    }

    public function getTaskContent()
    {

        // $content         = sprintf('<%s>以完成。任务奖励:', $usertask_all->details);
        $usertask_reward = $this->reward_info;
        if (empty($usertask_reward)) {
            $reward_content = '无';
        }

        if (array_get($usertask_reward, "gold")) {
            $reward_content = sprintf(" 金币+%s", array_get($usertask_reward, "gold"));
        }

        if (array_get($usertask_reward, "contribute")) {
            $reward_content = sprintf(" 贡献值+%s", array_get($usertask_reward, "gold"));
        }

        return $reward_content;
    }

    public function getUserTask($user_id, $date = null)
    {
        $userTaskBuild = UserTask::where(['task_id' => $this->id, 'user_id' => $user_id]);

        $default_date = Carbon::today();
        if (!empty($date)) {
            $default_date = $date;
        }

        if ($this->type == self::TIME_TASK) {
            $userTaskBuild = $this->getDateUserTask($userTaskBuild, $default_date);
        }

        return $userTaskBuild->first();
    }

    public function getDateUserTask($userTaskBuild, $date)
    {
        return $userTaskBuild->whereDate('created_at', $date);
    }

    /**
     * 接受任务
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function receiveTask(User $user, Task $task)
    {

        $taskExisted = $user->tasks()->wherePivot('task_id', $task->id)->exists();

        if (!$taskExisted) {

            $user->tasks()->attach($task->id, ['status' => UserTask::TASK_UNDONE]);
            Action::createAction('tasks', $task->id, $user->id);
        }
        return true;
    }

    /**
     * 重置状态与修改状态
     * @param User $user
     * @return int
     */
    public function checkTaskStatus($user)
    {
        $piovt = UserTask::firstOrNew([
            "task_id" => $this->id,
            "user_id" => $user->id,
        ]);

        if ($this->isDailyTask()) {
            if ($piovt->updated_at < today()) {
                $piovt->status = UserTask::TASK_UNDONE;
            }
        }

        if ($this->type != self::CUSTOM_TASK) {
            if ($piovt->status < UserTask::TASK_REACH) {
                $method = $this->resolve['method'] ?? null;
                if (!is_null($method) && method_exists($this, $method)) {
                    $args = $this->resolve['args'] ?? [];
                    try {
                        $taskDone = $this->$method(...$args);
                    } catch (Exception $e) {
                        \info($e->getMessage());
                    }
                    if ($taskDone) {
                        $piovt->fill(['status' => UserTask::TASK_REACH])->save();
                    }
                }
            }

        }

        return $piovt->status;
    }

    public function rewardTask(Task $task, User $user)
    {
        $pivot = \App\UserTask::firstOrNew([
            'user_id' => $user->id,
            'task_id' => $task->id,
        ]);
        if ($pivot->id) {
            if ($pivot->status == UserTask::TASK_REACH) {
                $process_reward = $pivot->processReward($task->reward_info);
                if ($process_reward) {
                    $pivot->status  = UserTask::TASK_DONE;
                    $pivot->content = sprintf('%s完成。奖励:', $task->details) . $task->getTaskContent();
                    $pivot->save();
                    $task->increment('count');
                    $task->save();
                }
            }
        }
    }
}
