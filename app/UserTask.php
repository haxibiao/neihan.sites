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

    public $incrementing = true;

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

    public function processReward($reward, $reward_rate = 1)
    {
        $task = $this->task;
        $user = $this->user;

        $name = $task->name;
        if ($name == "SleepMorning" || $name == "SleepNight") {
            $name = "睡觉任务";
        }
        try {
            DB::beginTransaction(); //开启数据库事务
            if (array_get($reward, "gold")) {
                $remark     = $reward_rate > 1 ? sprintf('%s %d倍奖励', $name, $reward_rate) : sprintf('%s奖励', $name);
                $rewardGold = $reward["gold"] * $reward_rate;
                $user->goldWallet->changeGold($rewardGold, $remark);
            }

            if (array_get($reward, "contribute")) {
                $remark     = $reward_rate > 1 ? sprintf('%s %d倍奖励', $name, $reward_rate) : sprintf('%s奖励', $name);
                $rewardGold = $reward['contribute'] * $reward_rate;
                Contribute::rewardUserContribute($user->id, $this->id, $rewardGold, "usertasks", $remark);
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
        $userTask = $task->getUserTask($user_id);

        //任务不存在,创建任务
        if (is_null($userTask)) {
            $userTask = UserTask::firstOrNew([
                'task_id' => $task_id,
                'user_id' => $user_id,
            ]);
            $userTask->save();

        } else {
            if ($task->isTimeTask() || $task->isDailyTask()) {
                if ($userTask->updated_at < today()) {
                    //更新状态\进度\完成时间
                    $userTask->status       = UserTask::TASK_UNDONE;
                    $userTask->progress     = 0;
                    $userTask->completed_at = null;
                    $userTask->save();
                    //强制更新
                    $userTask->touch();
                }
            }
        }

        return $userTask;
    }
}
