<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Vod\VodApi;

class ImportVod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:vod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import videos files into tecent vod ...';

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
        // $result = VodApi::upload(
        //     array(
        //         'videoPath' => '/data/www/ainicheng.com/public/storage/video/1.mp4',
        //     ),
        //     array(
        //         'videoName' => 'ainicheng_3.mp4',
        //         'procedure' => 'QCVB_SimpleProcessFile(1, 1, 10)',
        //     )
        // );
        // echo "upload to vod result: " . json_encode($result) . "\n";

        // $result = VodApi::getFileInfo("ainicheng_1");
        // echo "get file info result: " . json_encode($result) . "\n";

        
        $result = VodApi::deleteFile("5285890780571241337");
        echo "delete vod file result: " . json_encode($result) . "\n";
    }
}
