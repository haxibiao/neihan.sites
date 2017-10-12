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
        $db_name   = env('DB_DATABASE');

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

        if (empty($this->option('local'))) {
            RemoteFacade::into($db_server)->run($cmds, function ($line) {
                $this->comment($line);
            });
        }

        // $scp_command = 'rsync -P --rsh=ssh root@' . $db_server .
        //     ':' . $sql_data_folder . '/' . $db_name . '.sql.zip ' . $sql_data_folder;
        // $this->info($scp_command);
        // $this->info('复制粘贴上面的命令来拉取服务器上最新的数据库备份文件, then run art get:sql --local');
        // RemoteFacade::into('localhost')->run([$scp_command], function ($line) {
        //     $this->comment($line);
        // });

        // $sqlim = 'mysql -uroot -plocaldb001';

        // $cmds_local = array(
        //     'whoami 2>&1',
        //     'hostname 2>&1',
        //     'cd ' . $sql_data_folder,
        //     '[ -f ' . $db_name . '.sql.zip ] && unzip -o ' . $db_name . '.sql.zip',
        //     '[ -f ' . $db_name . '.sql ] && ' . $sqlim . ' ' . $db_name . '<' . $db_name . '.sql',
        //     'echo "数据库已恢复完成 ..."',
        // );
        
        // $this->local_run($cmds_local);
    }

    public function local_run($cmds)
    {
        $cmds_str = implode(' && ', $cmds);
        $do       = system($cmds_str);
    }
}
