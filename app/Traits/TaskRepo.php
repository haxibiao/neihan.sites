<?php

namespace App\Traits;

use App\UserTask;
use Carbon\Carbon;

trait TaskRepo
{

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

        if ($this->type == self::DAILY_TASK) {
            $userTaskBuild = $this->getDateUserTask($userTaskBuild, $default_date);
        }

        return $userTaskBuild->first();
    }

    public function getDateUserTask($userTaskBuild, $date)
    {
        return $userTaskBuild->whereDate('created_at', $date);
    }
}
