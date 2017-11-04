<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sql {--server=} {--db=} {--restore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get sql from sql and unzip and restore ...';

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
        if ($this->option('restore')) {
            return $this->restore();
        }
        $this->back_sql_on_server();
    }

    public function restore()
    {
        $db_name = $this->option('db') ? $this->option('db') : env('DB_DATABASE');
        $sql     = 'mysql -uroot -p' . env('DB_PASSWORD');
        $cmd     = $sql . ' ' . $db_name . '</data/sqlfiles/' . $db_name . '.sql';
        $do      = `$cmd`;
    }

    public function back_sql_on_server()
    {
        $db_name = $this->option('db') ? $this->option('db') : env('DB_DATABASE');
        $server  = $this->option('server') ? $this->option('server') : env('DB_SERVER');
        \SSH::into($server)->run([
            'whoami',
            'hostname',
            'cd /data/sqlfiles',
            'echo ' . $db_name . ' is dumping ..',
            'sqld ' . $db_name . '>' . $db_name . '.sql',
            'zip -r ' . $db_name . '.sql.zip ' . $db_name . '.sql',
        ]);
    }
}
