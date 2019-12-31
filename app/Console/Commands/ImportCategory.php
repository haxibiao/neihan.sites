<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:category {db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入其他系统的分类，比如:damei';

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
        if($db = $this->argument('db')) {
            $this->$db();
        }
    }

    function damei() {
        
    }
}
