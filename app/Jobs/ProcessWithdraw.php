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

    protected $description = "提现队列，走数据库job";
    public $tries          = 1;
    protected $withdraw;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
        $this->onQueue('withdraws');
        $this->onConnection('database');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->withdraw->process();
    }
}
