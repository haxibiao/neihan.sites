<?php

namespace App;

use App\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

class UserTask extends Pivot
{
    // use UserTaskRepo;

    protected $table = 'user_task';

    protected $fillable = [
        'id',
        'task_id',
        'user_id',
        'content',
        'status',
        'progress',
        'created_at',
        'updated_at',
        'completed_at',
    ];

    //任务状态
    const TASK_FAILED = -1; //失败
    const TASK_UNDONE = 0; //未完成
    const TASK_REVIEW = 1; //进行中
    const TASK_REACH  = 2; //未领取奖励
    const TASK_DONE   = 3; //完成

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function task()
    {
        return $this->belongsTo(\App\Task::class);
    }

    public function getStatus()
    {
        $status     = $this->status;
        $task       = $this->task;
        $type       = $task->type;
        $start_time = $task->getDailyStartTime();
        $end_time   = $task->getDailyEndTime();
        $now        = Carbon::now();
        if ($type == Task::TIME_TASK) {
            if ($this->status != UserTask::TASK_DONE) {
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
            }
        }

        return $status;
    }

    public function processReward($reward)
    {
        $task = $this->task;
        $user = $this->user;
        try {
            DB::beginTransaction(); //开启数据库事务
            if (array_get($reward, "gold")) {
                $remark     = sprintf('%s任务奖励', $task->details);
                $rewardGold = $reward["gold"];
                $user->goldWallet->changeGold($rewardGold, $remark);
            }

            if (array_get($reward, "contribute")) {
                $remark     = sprintf('%s任务奖励', $task->details);
                $rewardGold = $reward['contribute'];
                Contribute::rewardUserContribute($user->id, $this->id, $rewardGold, "usertasks");
            }

            DB::commit(); //事务提交
            return true;
        } catch (\Exception $ex) {
            DB::rollBack(); //数据库回滚
            return false;
        }
    }

    //创建每日任务
    public static function createUserTask($task_id, $user_id, $date)
    {
        $task     = Task::find($task_id);
        $usertask = $task->getUserTask($user_id);

        if (!$usertask) {
            $usertask = UserTask::firstOrCreate(
                ['task_id' => $task_id],
                ['user_id' => $user_id],
                ['created_at' => $date]);
        }
        return $usertask;
    }
}
