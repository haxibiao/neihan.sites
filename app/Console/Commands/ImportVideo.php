<?php

namespace App\Console\Commands;

use App\Video;
use Illuminate\Console\Command;
use Vod\VodApi;

class ImportVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:video {id=0} {--cos} {--vod}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import videos files into tencent cos / vod ...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        VodApi::initConf("AKIDPbXCbj5C1bz72i7F9oDMHxOaXEgsNX0E", "70e2B4g27wWr1wf9ON8ev1rWzC9rKYXH");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('id')) {
            $video = Video::find($this->argument('id'));
            $this->importVideo($video);
        } else {
            foreach (Video::all() as $video) {
                $this->importVideo($video);
            }
        }
    }

    public function importVideo($video)
    {
        if ($video) {
            if (starts_with($video->path, 'http')) {
                $this->comment("skip cos cdn path : " . $video->path);
                return;
            }
            if ($this->option('cos')) {
                $localPath = public_path($video->getPath());
                if (!file_exists($localPath)) {
                    $localPath = public_path($video->path);
                }
                if ($cdn_url = $this->cosUpload($localPath)) {
                    $video->path = $cdn_url;
                    $video->save();
                    $this->info("$video->id $video->path");
                }
            }
            if ($this->option('vod')) {
                $vod_url     = $this->vodUpload(public_path($video->getPath()), "ainicheng_" . $video->id . ".mp4");
                $video->path = $vod_url;
                $video->save();
                $this->info("$video->id $video->path");
            }

        } else {
            $this->error("video not exist");
        }
    }

    public function vodUpload($srcPath, $destFileName)
    {
        $this->comment($srcPath);
        if (!file_exists($srcPath)) {
            $this->error("$srcPath not exist");
            return false;
        }
        $result = VodApi::upload(
            array(
                'videoPath' => $srcPath, //'/data/www/ainicheng.com/public/storage/video/1.mp4',
            ),
            array(
                'videoName' => $destFileName, //'ainicheng_3.mp4',
                'procedure' => 'QCVB_SimpleProcessFile(1, 1, 10)',
            )
        );
        dd(json_encode($result));

        // $result = VodApi::getFileInfo("ainicheng_1");
        // echo "get file info result: " . json_encode($result) . "\n";

        // $result = VodApi::deleteFile("5285890780571241337");
        // echo "delete vod file result: " . json_encode($result) . "\n";
    }

    public function cosUpload($srcPath)
    {
        $this->comment($srcPath);
        if (!file_exists($srcPath)) {
            $this->error("$srcPath not exist");
            return;
        }
        $bucket = env('DB_DATABASE');
        $cos    = app('qcloudcos');
        $cos::createFolder($bucket, 'video');
        $dstPath = "video/" . PATHINFO($srcPath, PATHINFO_FILENAME) . "." . PATHINFO($srcPath, PATHINFO_EXTENSION);
        $this->info($dstPath);
        $result = $cos::upload($bucket, $srcPath, $dstPath);
        $res    = json_decode($result);
        if ($res && !empty($res->data->access_url)) {
            $this->info('上传成功! cdn access url:' . $res->data->access_url);
            return $res->data->access_url;
        } else {
            $this->comment($result);
            $this->error('上传失败');
        }
        return false;
    }
}
