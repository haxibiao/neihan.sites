<?php

namespace App\Console\Commands;

use Collective\Remote\RemoteFacade;
use Illuminate\Console\Command;

class GetSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sql {--server=} {--db=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'use ssh get mysql db latest backup and restore on local';

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
        $this->getsql();
    }

    private function getsql()
    {
        set_time_limit(-1);

        $db_server = env('DB_SERVER');
        $db_name   = env('DB_DATABASE');

        if ($this->option('server')) {
            $db_server = $this->option('server');
        }

        if ($this->option('db')) {
            $db_name = $this->option('db');
        }

        $sql_data_folder = '/data/sqlfiles';
        if (!is_dir($sql_data_folder)) {
            mkdir($sql_data_folder, 0777, 1);
        }

        $cmds = array(
            'echo "数据库正在服务器上备份 ..."',
            'whoami',
            'hostname',
            '[ -f ~/.bash_aliases ] && source ~/.bash_aliases',
            'cd ' . $sql_data_folder,
            'sqld ' . $db_name . '>' . $db_name . '.sql',
            'zip ' . $db_name . '.sql.zip ' . $db_name . '.sql',
        );

        RemoteFacade::into($db_server)->run($cmds, function ($line) {
            $this->comment($line);
        });
    }
}
