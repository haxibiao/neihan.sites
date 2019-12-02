<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserTask extends Pivot
{
    // use UserTaskRepo;

    protected $table = 'user_task';

    protected $fillable = [
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

}
