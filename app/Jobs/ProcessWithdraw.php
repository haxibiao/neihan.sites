<?php

namespace App\Jobs;

use App\User;
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
        if ($withdraw->amount >= 1 && $withdraw->to_platform != 'dongdezhuan') {
            $withdraw->processingFailedWithdraw('提现异常,已上报情况至后台,请等待处理~');
            $withdraw->wallet->user->update(['status' => User::STATUS_FREEZE]);
        }

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

        if ($this->withdraw->to_platform === 'dongdezhuan') {
            $this->withdraw->processDongdezhuan();
        } else {
            $this->withdraw->process();
        }
    }
}
