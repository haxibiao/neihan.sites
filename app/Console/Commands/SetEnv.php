<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:env {--db_database=} {--db_host=} {--pay : 是否配置支付能力}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置env的值(当前主要设置当前环境的一些安全设置)';

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
        //先覆盖最新的 .env.prod
        $envFile = app()->environmentFilePath();
        \file_put_contents($envFile, @file_get_contents($envFile . ".prod"));

        $this->setKV(['DB_PASSWORD' => @file_get_contents("/etc/sql_pass_dm")]);
        // $this->setKV(['MONGO_PASSWORD' => @file_get_contents("/etc/nosql_pass")]);

        //数据库
        if ($this->option("db_database")) {
            $this->setKV(['DB_DATABASE' => $this->option("db_database")]);
        }
        if ($this->option("db_host")) {
            $this->setKV(['DB_HOST' => $this->option("db_host")]);
        }

        $this->setKV(['LIVE_SECRET_KEY' => @file_get_contents("/etc/live_secret_dongdianyi")]);
        // //其他key
        $this->setKV(['SMS_APP_KEY' => @file_get_contents("/etc/sms_key_secret_dm")]);
        // $this->setKV(['COS_SECRET_KEY' => @file_get_contents("/etc/cos_secret_key")]);
        // $this->setKV(['MAIL_PASSWORD' => @file_get_contents("/etc/mailgun_mail_pass")]);
        // $this->setKV(['WECHAT_APP_SECRET' => @file_get_contents("/etc/wechat_app_secret_dm")]);
        // $this->setKV(['LIVE_KEY' => @file_get_contents("/etc/live_key_dm")]);
        // $this->setKV(['VOD_SECRET_KEY' => @file_get_contents("/etc/vod_secret_key")]);
        // $this->setKV(['REDIS_PASSWORD' => @file_get_contents("/etc/redis_pass")]);

        // //支付的
        // if ($this->option('pay')) {
        //     $this->warn("更新支付信息...");
        //     //答妹暂时没微信支付
        //     $this->setKV(['WECHAT_PAY_KEY' => @file_get_contents("/etc/wechat_pay_key")]);
        //     $this->setKV(['WECHAT_PAY_MCH_ID' => @file_get_contents("/etc/wechat_pay_mch_id")]);
        //     $this->setKV(['ALIPAY_PAY_APPID' => @file_get_contents("/etc/appid_alipay")]);
        // }

    }

    /**
     * 设置.env里key value
     */
    public function setKV(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $this->info("更新 $envKey");

                $str .= "\n"; // 确保.env最后一行有换行符
                $keyPosition       = strpos($str, "{$envKey}="); //找到要替换的行字符起始位
                $endOfLinePosition = strpos($str, "\n", $keyPosition); //那行的结束位
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // 如果不存在，就添加一行
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    //否则替换
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        // $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}
