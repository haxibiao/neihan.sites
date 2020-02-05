<?php

namespace App\Console\Commands;

use App\Jobs\ProcessWithdraw;
use App\Withdraw;
use Illuminate\Console\Command;

class WithdrawlProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdrawl:process {--withdrawId=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将长时间未处理的提现重新放置队列中';

    protected $count = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($withdrawId = $this->option('withdrawId')) {
            $withdraw = Withdraw::findOrFail($withdrawId);
            return $this->process($withdraw);
        } else {
            //暂时简单处理 可能会出现重复添加进队列, 太久之前的提现不处理了，兼容高额提现
            Withdraw::where('status', Withdraw::WAITING_WITHDRAW)
                ->whereNull('rate')
                ->whereDate('created_at', '>=', '2020-02-05')
                ->chunk(100, function ($withdraws) {
                    foreach ($withdraws as $withdraw) {
                        $this->process($withdraw);
                    }
                });

            $this->info('总共成功添加进队列:' . $this->count);
        }
    }

    private function process(Withdraw $withdraw)
    {
        $withdraw->refresh();

        // $user = $withdraw->user();
        // if ($user->isShuaZi) {
        //     return null;
        // }
        if ($withdraw->status == Withdraw::WAITING_WITHDRAW) {
            dispatch(new ProcessWithdraw($withdraw->id))->onQueue('withdraws');
            $this->count++;
            $message = sprintf('withdraw id %s push queue success', $withdraw->id);
            $this->info($message);
        }
    }
}
