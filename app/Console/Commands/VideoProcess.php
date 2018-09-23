<?php

namespace App\Console\Commands;

use App\Helpers\QcloudUtils;
use App\Video;
use Illuminate\Console\Command;

class VideoProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:process {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'process video in ways: local, vod(default)';

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
        if ($video_id = $this->option('id')) {
            $video = Video::findOrFail($video_id);
            return $this->processVideo($video);
        }
        $this->processDrafts();
    }

    public function processVideo($video)
    {
        $video->syncVodProcessResult();
        if(!$video->cover){
            //如果还没有截图 就重新执行调用截图接口
            $duration = $video->duration > 9 ? 9 : $video->duration;
            QcloudUtils::makeCoverAndSnapshots($video->fileId, $duration);
        }
        $this->info("$video->id $article->title $video->path $video->cover");
    }

    public function processDrafts()
    {
        $videos = Video::where('status', 0)->get();
        foreach ($videos as $video) {
            $this->processVideo($video);
        }
    }
}
