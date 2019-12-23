<?php

namespace App\Jobs;

use App\Withdraw;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWithdraw implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $withdraw;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($withdrawId)
    {
        $withdraw = Withdraw::find($withdrawId);
        if (is_null($withdraw)) {
            return;
        }

        $this->withdraw = $withdraw;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->withdraw->process();
//        $this->withdraw->processDongdezhuan();
    }
}
