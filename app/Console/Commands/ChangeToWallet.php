<?php

namespace App\Console\Commands;

use App\Exchange;
use App\Jobs\ReportUserEarningsToDDZ;
use App\User;
use App\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangeToWallet extends Command
{

    protected $signature = 'change:towallet';

    protected $description = '将所有的用户的医宝 兑换为 钱包里面的 余额';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::where('status', 0)->chunk(1000, function ($users) {
            foreach ($users as $user) {
                if ($user->gold > 50){
                    $this->exchangeToWallet($user);
                    $this->info($user->id.'：'.$user->wallet->balance);
                }
            }
        });
    }

    protected function exchangeToWallet($user)
    {
        //注意:此处默认为双精度 根据默认10000:1的兑换率  50医宝上下浮动兑换会出现差距0.01
        $amount = Exchange::computeAmount($user->gold);
        $amount = (float) number_format($amount, 2);

        //账户余额小于0 或者已经转换过的用户跳出
        if ($user->isExchangeToday) {
            return null;
        }

        $rmbWallet = $user->wallet;

        DB::beginTransaction();
        try {
            Exchange::changeToWallet($user, $user->gold, $rmbWallet);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error($ex);
            dump($ex->getMessage());
        }
    }
}
