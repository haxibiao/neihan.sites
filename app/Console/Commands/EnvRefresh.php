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
    protected $signature = 'env:refresh {--prod} {--staging} {--local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh .env';

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
        if ($this->option('staging')) {
            return $this->refresh_staging();
        }

        if ($this->option('prod')) {
            return $this->refresh_prod();
        }
        return $this->refresh_local();
    }

    public function refresh_local()
    {
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $this->info('clear local env BUGSNAG_API_KEY ...');
        $this->updateEnv([
            'BUGSNAG_API_KEY'   => '',
        ]);
    }

    public function refresh_staging()
    {
        $this->info('refreshing staging env ...');
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $this->updateWebConfig();

        //fix env config for staging
        $this->updateEnv([
            'APP_ENV'   => 'staging',
            'APP_DEBUG' => 'true',
            'DB_HOST'   => env('DB_SERVER_STAGING'),
            'DB_DATABASE'   => env('DB_DATABASE_STAGING'),
        ]);
    }

    public function refresh_prod()
    {
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $this->updateWebConfig();

        //fix env config for prod
        $this->updateEnv([
            'APP_ENV'   => 'prod',
            'APP_DEBUG' => 'false',
            'DB_HOST'   => env('DB_SERVER'),
        ]);
    }

    public function updateWebConfig()
    {
        $data = @file_get_contents('/etc/webconfig.json');
        if ($data) {
            $webconfig = json_decode($data);
            $this->updateEnv([
                'DB_USERNAME'   => $webconfig->db_user,
                'DB_PASSWORD'   => $webconfig->db_passwd,
                'MAIL_HOST'     => $webconfig->mail_host,
                'MAIL_USERNAME' => $webconfig->mail_user,
                'MAIL_PASSWORD' => $webconfig->mail_passcode,
            ]);
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
