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
        $this->processMissingCoverVideos();
    }

    public function processVideo($video)
    {
        $res = $video->syncVodProcessResult();
        //如果res为空 或duration = 0 表示该视频有问题
        if (empty($res) || empty($res['basicInfo']['duration'])) {
            $video->status = -1;
            return $video->save();
        }
        //如果还没有截图 就重新执行调用截图接口
        if (!$video->cover && !empty($video->qcvod_fileid)) {
            $this->info("$video->id $article->title $video->path");
            $duration = $video->duration > 9 ? 9 : $video->duration;
            QcloudUtils::makeCoverAndSnapshots($video->qcvod_fileid, $duration);
        }

    }

    public function processMissingCoverVideos()
    {
        $videos = Video::whereNotNull('qcvod_fileid')
            ->whereNull('cover')
            ->where('status', '>', -1)
            ->get();
        foreach ($videos as $video) {
            $this->processVideo($video);
            sleep(5);
        }
    }
}
