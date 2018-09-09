<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnvRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:refresh {--db_host=} {--db_database=} {--env=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh .env file, db_host is must to pass';

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
        if ($env = $this->option('env')) {
            return $this->$env();
        }
        return $this->local();
    }

    public function local()
    {
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $this->info('clear local env BUGSNAG_API_KEY ...');
        $this->updateEnv([
            'BUGSNAG_API_KEY' => '',
        ]);
    }

    public function staging()
    {
        $this->info('refreshing staging env ...');
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $db_host = $this->option('db_host');
        $this->updateWebConfig($db_host);

        //fix env config for staging
        $this->updateEnv([
            'APP_ENV'   => 'staging',
            'APP_DEBUG' => 'true',
            'DB_HOST'   => $db_host,
            'DB_DATABASE' => $this->option('db_database')
        ]);
    }

    public function prod()
    {
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $db_host = $this->option('db_host');
        $this->updateWebConfig($db_host);

        //fix env config for prod
        $this->updateEnv([
            'APP_ENV'   => 'prod',
            'APP_DEBUG' => 'false',
            'DB_HOST'   => $db_host,
            'DB_DATABASE' => $this->option('db_database')
        ]);
    }

    public function updateWebConfig($db_host = null)
    {
        $data = @file_get_contents('/etc/webconfig.json');
        if ($data) {
            $webconfig  = json_decode($data);
            $db_changes = [];
            if ($db_host) {
                if (is_array($webconfig->databases)) {
                    foreach ($webconfig->databases as $database) {
                        if ($database->db_host == $db_host) {
                            $db_changes = [
                                'DB_USERNAME' => $database->db_user,
                                'DB_PASSWORD' => $database->db_passwd,
                            ];
                        }
                    }
                }
            } else {
                $this->error("--db_hos is needed while refresh for environments other like local");
                return;
            }
            $changes = array_merge($db_changes, [
                'MAIL_HOST'     => $webconfig->mail_host,
                'MAIL_USERNAME' => $webconfig->mail_user,
                'MAIL_PASSWORD' => $webconfig->mail_passcode,
            ]);
            $this->updateEnv($changes);

        } else {
            $this->error('webconfig not found!');
        }
    }

    public function updateEnv($data = array())
    {
        if (!count($data)) {
            return;
        }

        $pattern = '/([^\=]*)\=[^\n]*/';

        $envFile  = base_path() . '/.env';
        $lines    = file($envFile);
        $newLines = [];
        foreach ($lines as $line) {
            preg_match($pattern, $line, $matches);

            if (!count($matches)) {
                $newLines[] = $line;
                continue;
            }

            if (!key_exists(trim($matches[1]), $data)) {
                $newLines[] = $line;
                continue;
            }

            $line       = trim($matches[1]) . "={$data[trim($matches[1])]}\n";
            $newLines[] = $line;
        }

        $newContent = implode('', $newLines);
        $put_size   = @file_put_contents($envFile, $newContent);
        if ($put_size) {
            $this->info('update env key value success');
        }
    }
}
