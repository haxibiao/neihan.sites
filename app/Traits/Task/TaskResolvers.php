<?php

namespace App\Traits;

use App\Action;
use App\Assignment;
use App\Exceptions\GQLException;
use App\Task;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait TaskResolvers
{
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    // 获取任务列表
    public function resolveTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();

        //单次查询一个分类的
        $type     = $args['type'];
        $task_ids = Task::whereType($type)->pluck('id');
        //确保指派数据正常
        Assignment::initAssignments($user);
        $assignments = $user->assignments()->with('task')->with('user')
            ->whereIn('task_id', $task_ids)->get();
        $tasks = [];
        foreach ($assignments as $assignment) {

            $task = $assignment->task;

            //过滤掉下架的任务不显示
            if ($task->status == Task::DISABLE) {
                continue;
            }

            //过滤完成后需要不显示的任务 ↓ $record 保存的是 tasks 表主键
            $notShowCompletedIds = [1, 3, 4, 6];
            if ($assignment->status == 3 && in_array($assignment->task_id, $notShowCompletedIds)) {
                continue;
            }

            //指派的 属性alias 过去给gql用
            $task->assignment = $assignment;
            $task->user       = $assignment->user;
            $tasks[]          = $task;
        }

        return $tasks;
    }

    // 喝水打卡任务列表
    public function resolveDrinkWaterTasks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user       = getUser();
        $task       = Task::where('name', 'DrinkWaterAll')->first();
        $assignment = $task->getAssignment($user->id);
        if (!$assignment) {
            $assignment = Assignment::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
            ]);
        }

        $resolve = $assignment->resolve;
        return $this->getDrinkWaterSubTasks($resolve);
    }

    // 睡觉打卡玩法获取
    public function resolveSleepTask($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user      = getUser();
        $sleepTask = Task::where('resolve->task_en', 'Sleep')->first();

        throw_if(is_null($sleepTask), GQLException::class, '睡觉任务不存在!');

        $task       = $sleepTask;
        $assignment = Assignment::firstOrCreate([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);
        $sleepCompletedAt = $assignment->completed_at;
        $status           = 1;

        if ($sleepCompletedAt >= today()) {

            $minutes       = $sleepTask->resolve['minutes'] ?? 15;
            $diffMinus     = Carbon::parse($sleepCompletedAt)->diffInMinutes();
            $toastDiffTime = $this->toastDiffTime($sleepCompletedAt, $minutes);
            $task->details = empty($toastDiffTime) ? $task->details : $toastDiffTime;

            //没到15分钟,无法打卡,状态为3
            if ($diffMinus < $minutes) {
                $status = 3;
            } else {
                $wakeUpTask          = Task::where('resolve->task_en', 'Wake')->first();
                $currrentIsSleepTask = $sleepTask->status == 1;
                $task                = $currrentIsSleepTask ? $sleepTask : $wakeUpTask;
            }
        }
        $assignment = Assignment::firstOrCreate([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);
        $assignment->status = $status;
        $assignment->save();
        return $task;
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    public function receiveTaskResolver($root, array $args, $context, $info)
    {
        $task = Task::where('id', $args['id'])
            ->first();
        $user       = getUser();
        $assignment = Assignment::firstOrNew([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);
        if (!$assignment->id) {
            $assignment->save();
            Action::createAction('tasks', $task->id, $user->id);
        }
        return $task;
    }

    // 所有喝水完成后的奖励
    public function resolveDrinkWaterReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userId     = getUserId();
        $task       = Task::where('name', 'DrinkWaterAll')->first();
        $assignment = $task->getAssignment($userId);

        throw_if($assignment->status == Assignment::TASK_DONE, GQLException::class, '奖励已经领取');
        throw_if($assignment->status != Assignment::TASK_REACH, GQLException::class, '任务还未完成');

        $assignment->status = Assignment::TASK_DONE;
        $assignment->save();

        return $assignment;
    }

    // 睡觉打卡奖励接口（老接口）
    public function resolveSleepReward($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user       = getUser();
        $task       = Task::find($args['task_id']);
        $assignment = $task->getAssignment($user->id);
        $isWakeCard = Arr::get($task->resolve, 'task_en') == "Wake";

        $sleepTask       = Task::where('resolve->task_en', 'Sleep')->first();
        $sleepAssignment = $sleepTask->getAssignment($user->id);

        $intervalMinutes = $sleepTask->resolve['minutes'] ?? 15;
        $wakeTask        = Task::where('resolve->task_en', 'Wake')->first();

        $taskOutOfTime = true;
        if ($sleepAssignment->completed_at) {
            $taskOutOfTime = Carbon::parse($sleepAssignment->completed_at)->diffInMinutes() > $intervalMinutes;
        }
        $taskInReview = $assignment->status == Assignment::TASK_REVIEW;

        if ($taskOutOfTime && $taskInReview) {
            $assignment->status       = Assignment::TASK_DONE;
            $assignment->completed_at = now();
            $assignment->content      = $isWakeCard ? $task->getTaskContent() : $task->name . "打卡成功,等待下次" . $wakeTask->name . "时领取奖励";
            $assignment->save();
        }

        //判断此次打卡的是否为起床卡,是起床卡,更改掉睡觉卡的状态为可打卡状态
        if ($isWakeCard) {
            $sleepAssignment->update(['status' => 1]);
            $assignment->increment('current_count');
        }
        return $assignment;
    }

    // 任务中心领取奖励接口
    public function getReWardResolver($root, array $args, $context, $info)
    {
        $user       = getUser();
        $task       = Task::findOrFail($args['id']);
        $assignment = \App\Assignment::firstOrNew([
            'user_id' => $user->id,
            'task_id' => $task->id,
        ]);
        //TODO:没有领奖动作
        if ($assignment->status == Assignment::TASK_REACH) {
            $assignment->status = Assignment::TASK_DONE;
            $assignment->save();
        }
        return $task;
    }

    // 喝水任务上报打卡接口 drinkWater,单次喝水成功后调用...
    public function resolveDrinkWater($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $userId = getUserId();

        // 此处的task_id代表喝的是第几杯水,兼容以前的设计
        $position = $args['task_id'];

        $task       = Task::where('name', 'DrinkWaterAll')->first();
        $assignment = $task->getAssignment($userId);

        // $resolve存放喝水的信息,如[1,2]代表喝了第一杯和第二杯
        $resolve = $assignment->resolve;

        $subTaskHasDone = $resolve && in_array($position, $resolve);
        throw_if($subTaskHasDone, GQLException::class, '已经完成了');

        // 校验第$position杯水是否已经开始
        $hour           = Carbon::now()->hour;
        $taskIsNotStart = ($position + 8 > $hour);
        throw_if($taskIsNotStart, GQLException::class, '还未开始');

        if (is_null($resolve)) {
            $resolve = [$position];
        } else {
            $resolve[] = $position;
        }
        $assignment->resolve       = $resolve;
        $assignment->current_count = count($resolve);
        $assignment->save();

        return $this->getDrinkWaterSubTasks($resolve);
    }

    // 应用商店好评任务接口
    public function highPraiseTaskResolver($root, array $args, $context, $info)
    {
        $user = checkUser();

        $task = Task::find($args['id']);
        throw_if(is_null($task), GQLException::class, '任务不存在哦~,请稍后再试');
        throw_if(empty(trim($args['content'])), GQLException::class, '账号不能为空哦~');

        return $this->highPraise($user, $task, $args['content']);
    }
}
