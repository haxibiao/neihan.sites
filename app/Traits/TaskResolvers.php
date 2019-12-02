<?php

namespace App\Traits;

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
            ])->first();
            $usertask->status   = 3;
            $usertask->progress = 1;
            $usertask->content  = $task->details . '完成';
            if (empty($usertask->completed_at)) {
                $usertask->completed_at = Carbon::now();
            }

            $usertask->save();

        }

        //更新总任务状态与进度
        $tasks_ids = Task::where('name', "DrinkWater")->get()->pluck('id')->toArray();

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
            $status  = 3;
            $content = '以完成';
        }

        $usertask_all->update([
            'content'      => $task_all->name . $content,
            'status'       => $status,
            'progress'     => $avg_progress,
            'completed_at' => $task_all_completed_at,
        ]);

        return $task;
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
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                ]);

                //laravel的bug上面的$usertask获取不到真正的对象
                $usertask = UserTask::where('user_id', $user->id)
                    ->where('task_id', $task->id)
                    ->whereDate('created_at', Carbon::today())->first();
            }
            $now = Carbon::now();

            //如果任务未完成
            if ($usertask->status != UserTask::TASK_DONE) {
                $status = 0;
                if ($now->greaterThan($start_time) && $now->lessThan($end_time)) {
                    //任务进行中
                    $status = UserTask::TASK_REVIEW;
                } else if ($now->greaterThan($end_time)) {
                    //大于结束时间为打卡失败
                    $status = UserTask::TASK_FAILED;
                } else if ($now->lessThan($start_time)) {
                    //小于开始时间为打卡未完成
                    $status = UserTask::TASK_UNDONE;
                }
                $usertask->status = $status;

                $usertask->save();
            }
        }

        //创建总任务对象   创建重构
        $task_all = Task::where('name', 'DrinkWaterAll')->first();

        $usertask_all = UserTask::where('user_id', $user->id)
            ->where('task_id', $task_all->id)
            ->whereDate('created_at', Carbon::today())->exists();

        if (!$usertask_all) {
            UserTask::firstOrCreate([
                'task_id' => $task_all->id,
                'user_id' => $user->id,
            ]);
        }

        return $tasks;
    }

    // public function resolveDrinkWater(){

    // }
}
