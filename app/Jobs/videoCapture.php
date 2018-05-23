<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class videoCapture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    protected $video_path,$cover;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($video_path, $cover)
    {
        $this->cover=$cover;
        $this->video_path=$video_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video_path=$this->video_path;
        $cover=$this->cover;

        $covers           = [];
        $cmd_get_duration = 'ffprobe -i ' . $video_path . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
        $duration         = `$cmd_get_duration`;
        $duration         = intval($duration);
        if ($duration > 15) {
            //take 8 covers jpg file, return first ...
            for ($i = 1; $i <= 8; $i++) {
                $seconds   = intval($duration * $i / 8);
                $cover_i   = $cover . ".$i.jpg";
                $cmd       = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $seconds -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover_i 2>&1";
                dd($cover_i);
                $exit_code = exec($cmd);
                if ($exit_code == 0) {
                    $covers[] = $cover_i;
                }
            }

            if (count($covers)) {
                //copy first screen as default cover..
                copy($covers[0], $cover);
            }

        }
    }
}
