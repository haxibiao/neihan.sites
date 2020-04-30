<?php

namespace App\Jobs;

use App\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DelayRewaredTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $assignment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userTaskId)
    {
        $this->assignment = Assignment::find($userTaskId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assignment = $this->assignment;
        if ($assignment) {
            $assignment->status = Assignment::TASK_DONE;
            $assignment->save();
        }
    }
}
