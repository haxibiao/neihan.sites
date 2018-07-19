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
        \App\Console\Commands\EnvRefresh::class,
        \App\Console\Commands\GetSql::class,
        \App\Console\Commands\ImageResize::class,
        \App\Console\Commands\ImageTitle::class,
        \App\Console\Commands\ImageLogo::class,
        \App\Console\Commands\SitemapRefresh::class,
        \App\Console\Commands\AdminSet::class,
        \App\Console\Commands\ImportVod::class,
        \App\Console\Commands\TestPerf::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sitemap:refresh')
                 ->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        require base_path('ops/commands.php'); //include fix:data etc ...
    }
}
