<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:set {uid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set admin user';

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
        if (!$this->argument('uid')) {
            dd('need uid...');
        } else {
            $user           = \App\User::findOrFail($this->argument('uid'));
            $user->is_admin = !$user->is_admin;
            $user->save();
            $this->info("结果: $user->name 现在" . ($user->is_admin ? "是" : "不是") . "　管理员");
        }
    }
}
