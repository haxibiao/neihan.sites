<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CosApk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cos:apk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload latest apk builds to cos for ainicheng';

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

        $app = "ainicheng";
        $cos = $this->getCos();
        $this->uploadCos($cos, $app);
    }

    public function getCos($location = null)
    {
        $cos = app('qcloudcos');
        //TODO::这个切换region的还不好用...
        if ($location) {
            \YueCode\Cos\CosApi::setRegion("gz");
        }
        return $cos;
    }

    public function uploadCos($cos, $app)
    {
        $bucket  = $app;
        $srcPath = "/data/app/$app/android/app/build/outputs/apk/app-release.apk";
        $dstPath = "/" . $app . ".apk";
        $this->comment('正在上传．．．' . $srcPath);
        $res = $cos::upload($bucket, $srcPath, $dstPath, null, null, "YES");
        $this->comment($res);
        $json = json_decode($res);
        if ($json && !empty($json->data)) {
            $this->info('上传成功! cos url:');
            $this->info($json->data->source_url);
        }
    }
}
