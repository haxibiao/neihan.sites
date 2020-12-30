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
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每日统计日活
        $schedule->command('user:active')->dailyAt('23:59');
        //$schedule->command('video:process')->everyMinute();
        //$schedule->command('video:process --codeinfo')->hourly();

        // 凌晨将金币转为钱包余额
        $schedule->command('change:towallet')->daily();
        //每天凌晨3点标记一次热门分类
        $schedule->command('mark:hotpost')->dailyAt('3:00');
        $schedule->command('recount:novadata')->dailyAt('3:00');
        //保存每个站当天的百度索引量
        $schedule->command('baidu:include')->dailyAt('4:00');
        if (is_prod_env()) {
            // 生成SiteMap
            $schedule->command('sitemap:generate')->dailyAt('3:00');

            // 归档seo流量
            $schedule->command('archive:traffic')->cron('0 */3 * * *');

            // 更新站群首页资源
            $schedule->command('cms:update')->cron('0 */3 * * *');
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
