<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class TrafficClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traffic:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clean up traffic data weekly ....';

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
        DB::delete("delete from traffic where created_at < ?", [now()->addWeeks(-1)]);
    }
}
