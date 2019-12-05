<?php

namespace App\Traits;

use App\Exceptions\GQLException;
use App\Task;
use App\UserTask;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait TaskResolvers
{

    public function resolveDrinkWaterCheck($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $task_id = $args['task_id'];

        $task = Task::find($task_id);

        if ($user = getUser()) {
            $usertask = UserTask::where([
                'user_id' => $user->id,
                'task_id' => $args['task_id'],
            ])->whereDate('created_at', Carbon::today())->first();
            $usertask->status   = 3;
            $usertask->progress = 1;
            $usertask->content  = $task->details . '以完成';
            if (empty($usertask->completed_at)) {
                $usertask->completed_at = Carbon::now();
            }

            $usertask->save();
        }

        //更新总任务状态与进度
        $drink_water_tasks = Task::where('name', "DrinkWater")->get();

        $tasks_ids = $drink_water_tasks->pluck('id')->toArray();

        $usertaskBuilder = UserTask::whereIn('task_id', $tasks_ids)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today());
        //处理最后完成时间
        $not_complete = $usertaskBuilder->whereNull('completed_at')->count();

        $usertaskBuilder = UserTask::whereIn('task_id', $tasks_ids)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today());

        //更新完成时间
        $task_all_completed_at = null;
        if (!$not_complete) {
            $completed_usertasks = $usertaskBuilder->orderBy('completed_at', 'desc')->first();

            $task_all_completed_at = $completed_usertasks->completed_at;
        }

        $task_all     = Task::where('name', 'DrinkWaterAll')->first();
        $usertask_all = UserTask::where([
            'user_id' => $user->id,
            'task_id' => $task_all->id,
        ])->whereDate('created_at', Carbon::today())->first();

        $content      = '未完成';
        $status       = 0;
        $avg_progress = $usertaskBuilder->avg('progress');
        if ($avg_progress == 1) {
            $status  = 2;
            $content = sprintf('%s以完成。未领取任务奖励', $usertask_all->details);
        }

        $usertask_all->update([
            'content'      => $content,
            'status'       => $status,
            'progress'     => $avg_progress,
            'completed_at' => $task_all_completed_at,
        ]);

        return $drink_water_tasks;
    }

    public function resolveDrinkWaterTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user  = getUser();
        $tasks = Task::Where('name', "DrinkWater")->get();

        foreach ($tasks as $task) {
            $start_time = $task->getDailyStartTime();
            //结束时间
            $end_time = $task->getDailyEndTime();

            ///时间处理
            $usertask = UserTask::where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->whereDate('created_at', Carbon::today())->first();

            if (!$usertask) {

                $usertask = UserTask::firstOrCreate([
                    'task_id'    => $task->id,
                    'user_id'    => $user->id,
                    'created_at' => Carbon::now(),
                ]);

                //laravel的bug上面的$usertask获取不到真正的对象
                $usertask = UserTask::where('user_id', $user->id)
                    ->where('task_id', $task->id)
                    ->whereDate('created_at', Carbon::today())->first();
            }
            //如果任务未完成
            $usertask->status = $usertask->getStatus();
            $usertask->save();

        }

        //创建总任务对象   创建重构
        $task_all = Task::where('name', 'DrinkWaterAll')->first();

        $usertask_all = UserTask::where('user_id', $user->id)
            ->where('task_id', $task_all->id)
            ->whereDate('created_at', Carbon::today())->exists();

        if (!$usertask_all) {
            UserTask::firstOrCreate([
                'task_id'    => $task_all->id,
                'user_id'    => $user->id,
                'created_at' => Carbon::now(),
            ]);
        }

        return $tasks;
    }

    public function resolveDrinkWaterReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $task      = Task::where('name', 'DrinkWaterAll')->first();
        $user      = getUser();
        $user_task = $task->getUserTask($user->id);

        if ($task->task_status == UserTask::TASK_DONE) {
            throw new GQLException('奖励已经领取');
        }

        if ($task->task_status == UserTask::TASK_REACH) {
            //更新任务状态
            $user_task->status  = 3;
            $user_task->content = sprintf('%s完成。奖励:', $task->details) . $task->getTaskContent();
            $user_task->save();

            //金币奖励
            $remark     = sprintf('%s任务奖励', $task->details);
            $rewardGold = $task->reward["gold"];
            $user->goldWallet->changeGold($rewardGold, $remark);
        } else {
            throw new GQLException('任务还未完成');
        }

        return $user_task;
    }

    public function resolveSleepCheck($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $task_id   = $args['task_id'];
        $user      = getUser();
        $task      = Task::where('id', $task_id)->first();
        $user_task = $task->getUserTask($user->id);

        if (!$user_task) {
            $user_task = UserTask::firstOrCreate([
                'task_id'    => $task_id,
                'user_id'    => $user->id,
                'created_at' => Carbon::now(),
            ]);
            $user_task = UserTask::find($user_task->id);
        }

        $status = $user_task->getStatus();
        //若未打晚上的卡，早晨的卡则不能打
        if ($task->name == "SleepMorning") {
            $sleep_night_task = Task::where('name', "SleepNight")->first()->getUserTask($user->id, Carbon::yesterday());
            if (!$sleep_night_task) {
                $status = -1;
            }
            $user_task->status = $status;
        }
        $user_task->save();
        return $task;
    }

    public function resolveSleepReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $task_id   = $args['task_id'];
        $user      = getUser();
        $task      = Task::where('id', $task_id)->first();
        $user_task = $task->getUserTask($user->id);

        if ($user_task->task_status == UserTask::TASK_DONE) {
            throw new GQLException('奖励已经领取');
        }

        if ($user_task->status != UserTask::TASK_REVIEW) {
            throw new GQLException('还未到任务时间');
        }

        $task               = $user_task->task;
        $user_task->status  = 3;
        $user_task->content = sprintf('%s完成。奖励:', $task->details) . $task->getTaskContent();
        $user_task->save();

        $reward = $task->reward_info;
        // 处理早晨的奖励变更
        if ($task->name == "SleepMorning") {

            $sleep_night_task = Task::where('name', "SleepNight")->first()->getUserTask($user->id, Carbon::yesterday());

            if (!$sleep_night_task) {
                $user_task->processReward($task->reward_info);
            }
        }

        return $user_task;
    }

    public function resolveTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $type      = $args['type'];
        $taskQuery = Task::query();
        switch ($type) {
            case 'Sleep':
                $taskQuery = $taskQuery->whereIn('name', [
                    "SleepMorning",
                    "SleepNight",
                ]);
                break;
            case 'All':
                //去掉喝水赚钱任务，不同于其他任务
                $taskQuery = $taskQuery->whereNotIn('name', [
                    'DrinkWater',
                    'DrinkWaterAll',
                ]);
                break;
        }

        return $taskQuery->get();
    }
}
