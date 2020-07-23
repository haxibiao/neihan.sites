<?php

namespace App\Console\Commands;

use App\Profile;
use Illuminate\Console\Command;

class BannedUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:banned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '封禁作弊用户';

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


        //
    }
}
