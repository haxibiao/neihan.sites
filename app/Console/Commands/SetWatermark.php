<?php

namespace App\Console\Commands;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;

class SetWatermark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:watermark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '视频资源水印添加';

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
     * @return int
     */
    public function handle()
    {
        // $ffmpeg = FFMpeg::create();
        // $watermarkPath = '/Users/gongguowei/data/www/neihan.sites/public/watermark.png';

        // $video = $ffmpeg->open('/Users/gongguowei/data/www/neihan.sites/public/test01.mp4');

        // $clip = $video->clip(TimeCode::fromSeconds(1), TimeCode::fromSeconds(5));
        // $clip->save(new X264('libmp3lame', 'libx264'), '/Users/gongguowei/data/www/neihan.sites/public/test04.mp4');

        // $video
        //     ->filters()
        //     ->watermark($watermarkPath, array(
        //         'position' => 'relative',
        //         'top' => 0,
        //         //'right' => 0,
        //     ));

        //$format = new X264('libmp3lame', 'libx264');

        // $video->save($format, '/Users/gongguowei/data/www/neihan.sites/public/test04.mp4');

        $inputVideo = public_path('test01.mp4');
        $outputVideo = public_path('test15.mp4');
        $watermark = public_path('watermark.png');

        //$wmarkvideo = "ffmpeg -i " . $inputVideo . " -y -f mpegts -vf '" . $watermark . ",scale=10000:10000[watermask];[in][watermask] overlay=0:0:enable='between(t,1,5)'[out]'" . $outputVideo;
        $wmarkvideo = 'ffmpeg -i '.$inputVideo.' -i '.$watermark.' -filter_complex \\' . ' "[0:v][1:v] overlay=10:10:enable='. "'" .'between(t,2,4)'."'" . '" ' . $outputVideo;

        exec($wmarkvideo);

        return 0;
    }
}
