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
            $completed_usertasks   = $usertaskBuilder->orderBy('completed_at', 'desc')->first();
            $task_all_completed_at = $completed_usertasks->completed_at;
        }

        $task_all     = Task::where('name', 'DrinkWaterAll')->first();
        $usertask_all = $task_all->getUserTask($user->id);

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
            $usertask = UserTask::createUserTask($task->id, $user->id, Carbon::now());
            $usertask = UserTask::createUserTask($task->id, $user->id, Carbon::now());
            //如果任务未完成
            $usertask->status = $usertask->getStatus();
            $usertask->save();
        }
        //创建总任务对象
        $task_all = Task::where('name', 'DrinkWaterAll')->first();
        UserTask::createUserTask($task_all->id, $user->id, Carbon::now());
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

    public function resolveSleepTask($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $user = getUser();

        $now   = now();
        $today = today()->startOfDay()->addHours(12);
        $name  = 'SleepMorning';
        if ($now->greaterThan($today)) {
            $name = 'SleepNight';
        } else {
            $name = 'SleepMorning';
        }

        $task      = Task::where('name', $name)->first();
        $user_task = UserTask::createUserTask($task->id, $user->id, now());
        $user_task = UserTask::createUserTask($task->id, $user->id, now());
        $status    = 3;

        $minutes = $task->resolve['minutes'] ?? 15;
        if (Carbon::parse($user_task->completed_at)->diffInMinutes() > $minutes) {
            $status = 1;
        }
        //若未打晚上的卡，早晨的卡则不能打
        // if ($task->name == "SleepMorning") {
        //     $sleep_night_task = Task::where('name', "SleepNight")->first()->getUserTask($user->id, Carbon::yesterday());
        //     if (!$sleep_night_task) {
        //         $status = -1;
        //     }
        // }

        $user_task->status = $status;
        $user_task->save();
        return $task;
    }

    public function resolveSleepReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $task_id   = $args['task_id'];
        $user      = getUser();
        $task      = Task::where('id', $task_id)->first();
        $user_task = $task->getUserTask($user->id);
        $minutes   = $task->resolve['minutes'] ?? 15;
        $seconds   = $minutes * 60;

        if (empty($user_task->completed_at)) {
            $user_task->completed_at = now();
        } else {

            $diffmi = Carbon::parse($user_task->completed_at)->diffInMinutes();
            $diffse = Carbon::parse($user_task->completed_at)->diffInSeconds(now());

            if ($diffmi < $minutes) {
                if ($seconds - $diffse < 60 && $seconds - $diffse > 0) {
                    $diffsecoend = 60 - $diffse;
                    throw new GQLException('已经睡过了,请' . $diffsecoend . '秒后来');
                } else {
                    $diffminutes = $minutes - $diffmi;
                    throw new GQLException('已经睡过了,请' . $diffminutes . '分钟后来');
                }
            }
        }

        $task                    = $user_task->task;
        $user_task->status       = 3;
        $user_task->completed_at = now();
        $user_task->content      = sprintf('%s完成。奖励:', $task->details) . $task->getTaskContent();
        $user_task->timestamps   = false;
        $user_task->save();

        $reward = $task->reward_info;

        $user_task->processReward($task->reward_info);
        return $user_task;
    }

    public function resolveTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $type      = $args['type'];
        $taskQuery = Task::where('status', Task::ENABLE)->where('type', '!=', Task::TIME_TASK);
        if ($type != 'All') {
            $taskQuery = $taskQuery->where('type', $type);
        }

        return $taskQuery->get();
    }

    public function receiveTaskResolver($root, array $args, $context, $info)
    {

        $task = Task::where('status', self::ENABLE)->where('id', $args['id'])->first();

        $user = getUser();
        if ($task->receiveTask($user, $task)) {
            return $task;
        }
    }

    public function getReWardResolver($root, array $args, $context, $info)
    {
        $task = Task::findOrFail($args['id']);
        $user = checkUser();
        $this->rewardTask($task, $user);
        $task->increment('count');
        return $task;
    }

    public function highPraiseTaskResolver($root, array $args, $context, $info)
    {
        $task = Task::find($args['id']);
        throw_if(is_null($task), GQLException::class, '任务不存在哦~,请稍后再试');
        throw_if(empty(trim($args['content'])), GQLException::class, '账号不能为空哦~');
        $user = checkUser();
        return $this->highPraise($user, $task, $args['content']);
    }
}
