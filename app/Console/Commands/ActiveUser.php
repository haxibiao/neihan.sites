<?php

namespace App\Console\Commands;

use App\Dimension;
use Illuminate\Console\Command;

class ActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计每日日活';

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
        $count = \App\User::where("updated_at", ">", now()->toDateString())->count();
        Dimension::track("每天新增用户", $count, '新增');
    }
}
