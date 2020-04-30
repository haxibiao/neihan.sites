<?php

namespace App;

use App\Traits\AssignmentAttrs;
use App\Traits\AssignmentRepo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Assignment extends Model
{

    use AssignmentAttrs;
    use AssignmentRepo;

    protected $table = 'assignments';

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

    protected $casts = [
        'resolve' => 'array',
    ];

    //任务状态
    const TASK_FAILED = -1; //失败
    const TASK_UNDONE = 0; //未完成

    const TASK_REVIEW = 1; //进行中 - 已指派
    const TASK_REACH  = 2; //未领取奖励 - 已完成
    const TASK_DONE   = 3; //完成 - 已奖励

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
            if ($this->status != Assignment::TASK_DONE) {
                $status = 0;
                if ($now->greaterThan($start_time) && $now->lessThan($end_time)) {
                    //任务进行中
                    $status = Assignment::TASK_REVIEW;
                } else if ($now->greaterThan($end_time)) {
                    //大于结束时间为打卡失败
                    $status = Assignment::TASK_FAILED;
                } else if ($now->lessThan($start_time)) {
                    //小于开始时间为打卡未完成
                    $status = Assignment::TASK_UNDONE;
                }
            }
        }

        return $status;
    }

    public function processReward($reward)
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
                $remark     = sprintf('%s奖励', $name);
                $rewardGold = $reward["gold"];
                $user->goldWallet->changeGold($rewardGold, $remark);
            }

            if (array_get($reward, "contribute")) {
                $remark     = sprintf('%s奖励', $name);
                $rewardGold = $reward['contribute'];
                Contribute::rewardUserContribute($user->id, $this->id, $rewardGold, "assignment", $remark);
            }
            DB::commit(); //事务提交
            return true;
        } catch (\Exception $ex) {
            DB::rollBack(); //数据库回滚
            return false;
        }
    }

    //创建每日任务
    public static function createUserTask($task_id, $user_id)
    {
        return Assignment::firstOrCreate([
            'task_id' => $task_id,
            'user_id' => $user_id,
        ]);
    }

    public static function getTypes()
    {
        return [
            self::TASK_FAILED => '失败',
            self::TASK_UNDONE => '未完成',
            self::TASK_REVIEW => '进行中',
            self::TASK_REACH  => '领奖',
            self::TASK_DONE   => '完成',
        ];
    }
}
