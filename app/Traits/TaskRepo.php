<?php

namespace App\Traits;

use App\Action;
use App\Exceptions\GQLException;
use App\Jobs\DelayRewaredTask;
use App\Task;
use App\User;
use App\UserTask;
use Carbon\Carbon;
use Illuminate\Support\Arr;

trait TaskRepo
{
    public function isDailyTask()
    {
        return $this->type == self::DAILY_TASK;
    }

    public function isTimeTask()
    {
        return $this->type == self::TIME_TASK;
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

    public function getTaskContent($reward_rate = 1)
    {

        $usertask_reward = $this->reward_info;
        if (empty($usertask_reward)) {
            return sprintf('%s完成。', $this->name);
        }
        $reward_content = '';
        if (array_get($usertask_reward, "gold")) {
            $reward_content = sprintf(" 金币+%s", array_get($usertask_reward, "gold") * $reward_rate);

        }

        if (array_get($usertask_reward, "contribute")) {
            $reward_content = $reward_content . sprintf(" 贡献值+%s", array_get($usertask_reward, "contribute") * $reward_rate);
        }

        //TODO 更新掉名字,用中文
        if ($this->name == "SleepMorning") {
            $name = "起床卡";
        }
        if ($this->name == "SleepNight") {
            $name = "睡觉卡";
        }

        $reward_info = sprintf('%s完成。奖励:', $this->name);

        if ($reward_rate > 1) {
            $reward_info = sprintf('%s完成。' . $reward_rate . '倍奖励:', $this->name);
        }

        return $reward_info . $reward_content;
    }

    //获取所有子任务的queryBuild
    public function getchildrenBuild($user_id)
    {
        $childrenTasks_id = $this->childrenTasks()->pluck('id')->toArray();

        $childrenTasksBuild = UserTask::where('user_id', $user_id)->whereIn('task_id', $childrenTasks_id);
        if ($childrenTasksBuild->get()) {
            $firstchilderentask = $childrenTasksBuild->first()->task;
            if ($firstchilderentask->type == self::TIME_TASK) {
                $childrenTasksBuildClone = $childrenTasksBuild->whereDate('updated_at', today());
            }
        }
        return $childrenTasksBuildClone;
    }

    public function getUserTask($user_id, $chilren = false)
    {
        $userTaskBuild = UserTask::where(['task_id' => $this->id, 'user_id' => $user_id]);
        //时间任务
        if ($this->isTimeTask() || $this->isDailyTask()) {
            $userTaskBuild = $userTaskBuild->whereDate('updated_at', today());
        }

        //获取所有的子任务
        if ($chilren) {
            return $this->getchildrenBuild($user_id)->get();
        }
        return $userTaskBuild->first();
    }

    //获取子任务的最后完成时间
    public function getparentTaskComplete($user_id)
    {
        $usertaskBuilder = $this->getchildrenBuild($user_id);
        //处理最后完成时间
        $complete_count  = $usertaskBuilder->whereNull('completed_at')->count();
        $usertaskBuilder = $this->getchildrenBuild($user_id);

        if (!$complete_count) {
            $completed_usertasks = $usertaskBuilder->orderBy('completed_at', 'desc')->first();
            return $completed_usertasks->completed_at;
        }

        return null;
    }

    //获取所有子任务进度
    public function getparentTaskProgress($user_id)
    {
        $usertaskBuilder = $this->getchildrenBuild($user_id);
        return $usertaskBuilder->avg('progress');
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

    //为了兼容app 2.8版本喝水赚钱
    public function updateDrinkUserTask($user)
    {
        //app 2.8版本兼容喝水赚钱
        $isDrinkWater = getAppVersion() < '2.9.0' ? Arr::get($this->resolve, 'task_en') == "DrinkWaterAll" : false;

        if ($isDrinkWater) {
            $piovt = UserTask::createUserTask($this->id, $user->id, now());
            if ($piovt->updated_at < today()) {
                $piovt->status       = UserTask::TASK_UNDONE;
                $piovt->progress     = 0;
                $piovt->completed_at = null;
                $piovt->save();
                //强制更新
                $piovt->touch();
            }
        }
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

        // if ($this->name == "喝水赚钱") {
        //     dd($piovt);
        // }

        //为了兼容app 2.8版本喝水赚钱
        $this->updateDrinkUserTask($user);

        if ($this->isDailyTask()) {
            if ($piovt->updated_at < today()) {
                $piovt->status       = UserTask::TASK_UNDONE;
                $piovt->progress     = 0;
                $piovt->completed_at = null;
                $piovt->save();
                //强制更新
                $piovt->touch();
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
                        $piovt->fill(['status' => UserTask::TASK_REACH]);
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
                    $pivot->content = $task->getTaskContent();
                    $pivot->save();
                    $task->increment('count');
                    $task->save();
                }
            }
        }
    }

    /**
     * @param User $user
     * @param Task $task
     * @param string $content
     * @return bool
     * @throws GQLException
     */
    public function highPraise(User $user, Task $task, string $content): bool
    {
        $qb = UserTask::where([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);

        if ($qb->doesntExist()) {
            $user->tasks()->attach($task->id, ['status' => UserTask::TASK_UNDONE]);
        }
        $userTask = $qb->first();

        if ($userTask->status > UserTask::TASK_UNDONE) {
            throw new GQLException('好评任务已经做过了哦~');
        }

        $userTask->content = $content;
        $userTask->status  = UserTask::TASK_REVIEW;
        $saveStatus        = $userTask->save();
//        无需审核，1分钟后任务自动完成
        dispatch(new DelayRewaredTask($userTask->id))->delay(now()->addMinute(1));
        return $saveStatus;
    }
}
