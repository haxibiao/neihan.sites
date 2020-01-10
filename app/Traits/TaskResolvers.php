<?php

namespace App\Traits;

use App\DDZ\AppTask;
use App\Exceptions\GQLException;
use App\Task;
use App\UserTask;
use App\Visit;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait TaskResolvers
{

    //喝水任务上报打卡接口 drinkWater,单次喝水成功后调用...
    public function resolveDrinkWater($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $task_id  = $args['task_id'];
        $task     = Task::find($task_id);
        $user     = getUser();
        $user_id  = $user->id;
        $usertask = UserTask::createUserTask($task_id, $user_id, now());
        throw_if($usertask->status == UserTask::TASK_DONE, GQLException::class, '已经完成了');
        throw_if($usertask->status == UserTask::TASK_UNDONE, GQLException::class, '还未开始');
        $usertask->status       = 3;
        $usertask->progress     = 1;
        $usertask->content      = $task->getTaskContent();
        $usertask->completed_at = $usertask->completed_at ?? Carbon::now();
        $usertask->save();

        //更新总喝水赚钱任务的状态
        $parentTask     = $task->parentTasks;
        $parentUserTask = UserTask::createUserTask($parentTask->id, $user_id, now());

        //获取八个喝水任务的进度
        $avg_progress = $parentTask->getparentTaskProgress($user->id);
        //若所有的已经完成,更新总任务的状态
        if ($avg_progress == 1) {
            $parentUserTask->content = sprintf('%s以完成.', $parentTask->name);
            $parentUserTask->status  = UserTask::TASK_REACH;
            //更新总任务最后完成时间
            $parentUserTask->completed_at = $parentTask->getparentTaskComplete($user->id);
        }
        //更新总任务的进度
        $parentUserTask->progress = $avg_progress;
        $parentUserTask->save();
        //完成的次数
        $doneCount = $parentTask->getchildrenBuild($user_id)->whereNotNull('completed_at')->count();
        //TODO: 单次喝水打卡或者补卡成功, 更新次数到工厂
        if (getAppVersion() >= '2.9.0') {
            AppTask::countHeshuiTaskDone($user, $doneCount);
        }
        return $parentTask->childrenTasks;
    }

    public function resolveDrinkWaterTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user     = getUser();
        $task_all = Task::where('resolve->task_en', 'DrinkWaterAll')->first();
        $tasks    = $task_all->childrenTasks;

        foreach ($tasks as $task) {
            $usertask = UserTask::createUserTask($task->id, $user->id, Carbon::now());
            //实时更新任务状态
            $usertask->status = $usertask->getStatus();
            $usertask->save();
        }
        //创建总任务,用于统计其他子任务
        UserTask::createUserTask($task_all->id, $user->id, Carbon::now());
        return $tasks;
    }

    //所有喝水完成后的奖励
    public function resolveDrinkWaterReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //总喝水任务卡
        // $taskDrinkAll = Task::where('name', 'DrinkWaterAll')->first();
        $taskDrinkAll = Task::where('resolve->task_en', 'DrinkWaterAll')->first();
        $user         = getUser();
        $userTask     = UserTask::createUserTask($taskDrinkAll->id, $user->id, Carbon::now());
        throw_if($userTask->status == UserTask::TASK_DONE, GQLException::class, '奖励已经领取');
        throw_if($userTask->status != UserTask::TASK_REACH, GQLException::class, '任务还未完成');

        if ($userTask->status == UserTask::TASK_REACH) {
            $userTask->status  = 3;
            $userTask->content = $taskDrinkAll->getTaskContent();
            $userTask->save();
            //领取奖励
            $userTask->processReward($taskDrinkAll->reward_info);
            //TODO: 需要奖励8*2=16贡献
        }

        return $userTask;
    }

    //老接口
    public function resolvesleepTask($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $user      = getUser();
        $sleepTask = Task::where('resolve->task_en', 'Sleep')->first();
        throw_if(is_null($sleepTask), GQLException::class, '睡觉任务不存在!');

        $task              = $sleepTask;
        $sleepUserTask     = UserTask::createUserTask($sleepTask->id, $user->id, now());
        $status            = 1;
        $sleepCompleted_at = $sleepUserTask->completed_at;
        //判断今天打没打卡,如果没打卡,默认为睡觉卡,且未打卡,status为3
        if ($sleepCompleted_at >= today()) {
            //打了卡判断打卡的间隔时间
            $minutes       = $sleepTask->resolve['minutes'] ?? 15;
            $diffmi        = Carbon::parse($sleepCompleted_at)->diffInMinutes();
            $toastDiffTime = $this->toastDiffTime($sleepCompleted_at, $minutes);
            $task->details = empty($toastDiffTime) ? $task->details : $toastDiffTime;
            //没到15分钟,无法打卡,状态为3
            if ($diffmi < $minutes) {
                $status = 3;
            } else {
                $task = $sleepUserTask->status == 1 ? $sleepTask : Task::where('name', "起床卡")->first();
            }
        }

        $user_task         = UserTask::createUserTask($task->id, $user->id, now());
        $user_task->status = $status;
        $user_task->save();
        return $task;
    }

    //返回给前段消息提醒,还剩剩余多少时间
    public function toastDiffTime($completed_at, $minutes)
    {
        $seconds = $minutes * 60;
        $diffmi  = Carbon::parse($completed_at)->diffInMinutes();
        $diffse  = Carbon::parse($completed_at)->diffInSeconds(now());
        if ($diffmi < $minutes) {
            if ($seconds - $diffse < 60 && $seconds - $diffse > 0) {
                $diffsecoend = 60 - $diffse;
                return '请' . $diffsecoend . '秒后来';
            } else {
                $diffminutes = $minutes - $diffmi;
                return '请' . $diffminutes . '分钟后来';
            }
        }
        return null;
    }

    //老接口
    public function resolveSleepReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user          = getUser();
        $task          = Task::find($args['task_id']);
        $user_task     = $task->getUserTask($user->id);
        $sleepTask     = Task::where('resolve->task_en', 'Sleep')->first();
        $sleepUserTask = $sleepTask->getUserTask($user->id);
        $minutes       = $sleepTask->resolve['minutes'] ?? 15;
        $wakecard      = Arr::get($task->resolve, 'task_en') == "Wake";
        $wakeTask      = Task::where('resolve->task_en', 'Wake')->first();
        //判断睡觉卡第一次没有打卡,或者判断打了卡是否超过规定的15分钟
        $diffmi = empty($sleepUserTask->completed_at) ? true : Carbon::parse($sleepUserTask->completed_at)->diffInMinutes() > $minutes;
        if ($diffmi && $user_task->status == UserTask::TASK_REVIEW) {
            $user_task->status       = 3;
            $user_task->completed_at = now();
            $user_task->content      = $wakecard ? $task->getTaskContent() : $task->name . "打卡成功,等待下次" . $wakeTask->name . "时领取奖励";
            $user_task->save();
            //给予奖励
            $user_task->processReward($task->reward_info);

        }

        $countOfSleep = 1;
        //判断此次打卡的是否为起床卡,是起床卡,更改掉睡觉卡的状态为可打卡状态
        if ($wakecard) {
            $countOfSleep++;
            $sleepUserTask->update(['status' => 1]);
            //统计打卡次数
            Visit::createvisits($user->id, $task->id, 'tasks');
        }
        //TODO: 更新睡觉次数到工厂里
        if (getAppVersion() >= '2.9.0') {
            AppTask::countShuijiaoTaskDone($user, $countOfSleep);
        }
        return $user_task;
    }

    public function resolveTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $type      = $args['type'];
        $taskQuery = Task::where('status', Task::ENABLE)->where('type', '!=', Task::TIME_TASK);

        // $ids     = $taskQuery->whereIn('resolve->task_en', ['SleepAll', 'DrinkWaterAll', 'RewardVideo'])->pluck('id');
        $version = getAppVersion();
        if ($type != 'All') {
            $taskQuery = $taskQuery->where('type', $type);
            if ($version >= '2.9.0') {
                if ($type == 1) {
                    $taskQuery->orWhereIn('resolve->task_en', ['SleepAll', 'DrinkWaterAll', 'RewardVideo']);
                }
                if ($type == 2) {
                    $taskQuery->whereNotIn('resolve->task_en', ['SleepAll', 'DrinkWaterAll', 'RewardVideo']);
                }
            }
        }

        $tasks = $taskQuery->get();

        foreach ($tasks as $task) {
            $taskEn      = Arr::get($task->resolve, 'task_en');
            $isSleepTask = $taskEn == 'SleepAll' || $taskEn == 'DrinkWaterAll' || $taskEn == 'RewardVideo';
            if ($isSleepTask && $version >= '2.9.0') {
                $task->type = 1;
            }
        }

        return $tasks;
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

    public function doubleReWardResolver($root, array $args, $context, $info)
    {
        $user       = getUser();
        $task       = Task::findOrFail($args['id']);
        $rewardRate = 2;
        $user_task  = $task->getUserTask($user->id);
        throw_if(is_null($task), GQLException::class, '任务不存在哦~,请稍后再试');
        throw_if($user_task->reward_rate >= $rewardRate, GQLException::class, '领取失败,签到双倍奖励已领取过!');
        $user_task->content = $task->getTaskContent($rewardRate);
        $user_task->processReward($task->reward_info, $rewardRate);
        $user_task->reward_rate  = $rewardRate;
        $user_task->completed_at = now();
        $user_task->save();
        return $user_task;
    }
}
