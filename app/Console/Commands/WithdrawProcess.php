<?php

namespace App\Console\Commands;

use App\Jobs\ProcessWithdraw;
use App\Withdraw;
use Illuminate\Console\Command;

class WithdrawProcess extends Command
{

    protected $signature = 'withdraw:process {--withdrawId=}';

    protected $description = '将长时间未处理的提现重新放置队列中';

    protected $count = 0;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option('withdrawId')) {
            $withdraw = Withdraw::find($this->option('withdrawId'));
            return $this->process($withdraw);
        } else {
            //暂时简单处理 可能会出现重复添加进队列
            Withdraw::where('status', Withdraw::WAITING_WITHDRAW)->chunk(100, function ($withdraws) {
                foreach ($withdraws as $withdraw) {
                    $this->process($withdraw);
                }
            });

            $this->info('总共成功添加进队列:' . $this->count);
        }
    }

    public function process(Withdraw $withdraw)
    {
        $user = $withdraw->user;
        dispatch(new ProcessWithdraw($withdraw->id))->onQueue('withdraws');

        $this->count++;
        $this->info("push withdraw ID {$withdraw->id} in queue success");
    }
}
