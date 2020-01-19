<?php

namespace App\Console\Commands;

use App\Withdraw;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessWithdrawLimitPlaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:withdrawLimitPlaces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天凌晨核算提现限量抢';

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
        //限量抢的核算
        $withdraws = Withdraw::whereNotNull('rate')
            ->whereDate('created_at',Carbon::yesterday())
            ->where('status',Withdraw::WAITING_WITHDRAW)
            ->get();

        if( !$withdraws ){
            return;
        }
        $grouped = $withdraws->groupBy('amount');

        //核算3元限量抢
        $withdrawThree = $grouped->get('3.00');
        Withdraw::progressWithdrawLimit($withdrawThree,'3.00');

        //核算5元限量抢
        $withdrawFive  = $grouped->get('5.00');
        Withdraw::progressWithdrawLimit($withdrawFive,'5.00');

        //核算10元限量抢
        $withdrawTen   = $grouped->get('10.00');
        Withdraw::progressWithdrawLimit($withdrawTen,'10.00');

    }
}
