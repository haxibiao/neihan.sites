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
    protected $signature = 'get:sql {--local}';

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
        $db_name = env('DB_DATABASE');

        if (empty($this->option('local'))) {
            RemoteFacade::into($db_server)->run(array(
                'pwd', 'hostname',
                '[ -f ~/.bash_aliases ] && source ~/.bash_aliases',
                'cd /data/sqlfiles',
                'sqld ' . $db_name . '>' . $db_name . '.sql',
                'zip ' . $db_name . '.sql.zip ' . $db_name . '.sql',
            ), function ($line) {
                $this->comment($line);
            });
        }

        $scp_command = 'rsync -P --rsh=ssh root@' . $db_server . ':/data/sqlfiles/' . $db_name . '.sql.zip .';
        $this->comment($scp_command);

        RemoteFacade::into('homestead')->run(array(
            'pwd', 'hostname',
            '[ -f ~/.bash_aliases ] && source ~/.bash_aliases',
            'cd /data/sqlfiles',
            'echo "scp sql.zip maybe slow, you can art get:sql --local again"',
            $scp_command,
            'echo "scp finished ..."',
            '[ -f ' . $db_name . '.sql.zip ] && unzip -o ' . $db_name . '.sql.zip',
            '[ -f ' . $db_name . '.sql ] && sqlim ' . $db_name . '<' . $db_name . '.sql',
        ), function ($line) {
            $this->info($line);
        });

    }
}
