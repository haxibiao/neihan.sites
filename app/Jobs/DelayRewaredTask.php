<?php

namespace App\Jobs;

use App\UserTask;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DelayRewaredTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userTask;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userTaskId)
    {
        $this->userTask = UserTask::find($userTaskId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!is_null($this->userTask)) {
            $this->userTask->status = UserTask::TASK_REACH;
            $this->userTask->save();
        }
    }
}
