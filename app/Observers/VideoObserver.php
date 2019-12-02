<?php

namespace App\Observers;

use App\Jobs\MakeVideoCovers;
use App\Video;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function created(Video $video)
    {
        //启动截取图片job
        // MakeVideoCovers::dispatch($video);

    }

    /**
     * Handle the video "updated" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function updated(Video $video)
    {
        //也截图，改动视频，多半动视频文件，统一后，不会忘记在其他repo 方法里 截图

    }

    /**
     * Handle the video "deleted" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function deleted(Video $video)
    {
        //
    }

    /**
     * Handle the video "restored" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

    /**
     * Handle the video "force deleted" event.
     *
     * @param  \App\Video  $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
        //
    }
}
