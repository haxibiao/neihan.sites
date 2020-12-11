<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Movie;

class FixData extends Command
{

    protected $signature = 'fix:data {table}';

    protected $description = 'fix dirty data by table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        if ($table = $this->argument('table')) {
            return $this->$table();
        }
        return $this->error("必须提供你要修复数据的table");
    }

    function movies() {
        $this->info("清空之前的movies");
        Movie::truncate();
    }
}
