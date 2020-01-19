<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('sitemap:refresh')->daily();
        //$schedule->command('video:process')->everyMinute();
        //$schedule->command('video:process --codeinfo')->hourly();

        // 凌晨将金币转为钱包余额
        $schedule->command('change:towallet')->daily();
        //每天凌晨3点标记一次热门分类
        $schedule->command('mark:hotpost')->dailyAt('3:00');
        $schedule->command('recount:novadata')->dailyAt('3:00');

        if (is_prod_env()) {
            //限量抢核算脚本
            $schedule->command('process:withdrawLimitPlaces')->dailyAt('00:15');
        }
        // 凌晨将 处理 提现等待过久的
        // $schedule->command('withdraw:process')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
