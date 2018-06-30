<?php

namespace App\Jobs;

use App\Video;
use App\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TakeScreenshots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    // public $timeout = 600;  //TODO:: need pcntl PHP extension!!!

    protected $video;
    protected $force;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Video $video, $force = false)
    {
        $this->video = $video;
        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        $video        = \App\Video::findOrFail($this->video->id);
        $article = $video->article;
        $article->image_url = '/storage/video/' . $video->id . '.jpg';
        $video_path = starts_with($video->path, 'http') ? $video->path : public_path($video->path);
        $cover      = public_path($article->image_url);

        //get duration 
        $covers           = [];
        $cmd_get_duration = 'ffprobe -i ' . $video_path . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
        $duration         = `$cmd_get_duration`;
        $duration         = intval($duration);
        $video->duration  = $duration;

        //TODO:: less than 15s video need cleanup or warinig ...
        if ($duration > 8) {
            //take 8 covers jpg file, return first ...
            for ($i = 1; $i <= 8; $i++) {
                $seconds = intval($duration * $i / 8);
                $cover_i = $cover . ".$i.jpg";
                if ($this->force || !file_exists($cover_i)) {
                    $cmd       = "ffmpeg -ss $seconds -i $video_path -an -s 300x200  -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover_i 2>&1";
                    var_dump($cmd);
                    $exit_code = exec($cmd);
                    $covers[] = $cover_i;
                }
            }
            if (count($covers)) {
                //copy first screen as default cover..
                copy($covers[0], $cover);
                $video->status     = 1; //1代表可用
                $video->timestamps = false;
                $video  ->save();
                $article->status = 1;  //1代表可用
                $article->save();
            }
        }
    }
}
