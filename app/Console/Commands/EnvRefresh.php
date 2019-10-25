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
        $this->info('refreshing local env ...');
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));

        $this->updateWebConfig();

        $this->updateEnv([
            'APP_ENV'          => 'local',
            'APP_DEBUG'        => 'true',
            'FILESYSTEM_CLOUD' => 'public',
            'DB_HOST'          => 'localhost',
            'DB_DATABASE'      => env('APP_NAME'),
        ]);

    }

    public function develop()
    {
        $this->info('refreshing develop env ...');
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $db_host = $this->option('db_host');
        $this->updateWebConfig($db_host);

        $this->updateEnv([
            'APP_ENV'          => 'develop',
            'APP_DEBUG'        => 'true',
            'FILESYSTEM_CLOUD' => 'public',
            'LOCAL_APP_URL'    => 'http://develop.'.env('APP_NAME').'.com',
            'DB_HOST'          => $db_host,
            'DB_DATABASE'      => $this->option('db_database'),
        ]);
    }

    public function staging()
    {
        $this->info('refreshing staging env ...');
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $db_host = $this->option('db_host');
        $this->updateWebConfig($db_host);

        $this->updateEnv([
            'APP_ENV'          => 'staging',
            'APP_DEBUG'        => 'true',
            'FILESYSTEM_CLOUD' => 'public',
            'DB_HOST'          => $db_host,
            'DB_DATABASE'      => $this->option('db_database'),
        ]);
    }

    public function prod()
    {
        file_put_contents(base_path('.env'), file_get_contents(base_path('.env.local')));
        $db_host = $this->option('db_host');
        $this->updateWebConfig($db_host);

        //fix env config for prod
        $this->updateEnv([
            'APP_ENV'          => 'prod',
            'APP_DEBUG'        => 'false',
            'DB_HOST'          => $db_host,
            'FILESYSTEM_CLOUD' => 'cosv5',
            'DB_DATABASE'      => $this->option('db_database'),
        ]);
    }

    public function updateWebConfig($db_host = null)
    {
        $data = @file_get_contents('/etc/webconfig.json');
        if ($data) {
            $webconfig  = json_decode($data);
            $db_changes = [];
            if (isset($webconfig->db_host)) {
                //支持线上服务器使用默认本地数据库的情况
                if (\is_prod_env()) {
                    if (empty($db_host) || $webconfig->db_host == $db_host) {
                        $db_changes = [
                            'DB_PASSWORD' => $webconfig->db_passwd,
                        ];
                    }
                }
            }

            // db
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
                    $this->info("updating env file with $db_host settings ...");
                }
            }

            //cos
            $cos_changes = [];
            if (is_array($webconfig->coses)) {
                foreach ($webconfig->coses as $cos) {
                    if ($cos->bucket == env('APP_NAME')) {
                        $cos_changes = [
                            'COS_APP_ID'     => $cos->appid,
                            'COS_REGION'     => $cos->region,
                            'COS_LOCATION'   => $cos->location,
                            'COS_SECRET_ID'  => $cos->cos_secret_id,
                            'COS_SECRET_KEY' => $cos->cos_secret_key,
                        ];
                    }
                }
                $this->info("updating env file with $db_host settings ...");
            }

            //mail sms ...
            $changes = array_merge($cos_changes, $db_changes, [
                'MAIL_HOST'                    => $webconfig->mail_host,
                'MAIL_USERNAME'                => $webconfig->mail_username,
                'MAIL_PASSWORD'                => $webconfig->mail_password,
                'QCLOUD_SMS_ACCESS_KEY_ID'     => $webconfig->qcloud_sms_key_id,
                'QCLOUD_SMS_ACCESS_KEY_SECRET' => $webconfig->qcloud_sms_key_secret,
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
            $changes_count = count($data);
            $this->info("update env $changes_count value success");
        }
    }
}
