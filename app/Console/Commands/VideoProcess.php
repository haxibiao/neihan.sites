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
    protected $signature = 'video:process {--id=} {--cover} {--codeinfo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理vod上的视频，--id= 可以单独处理某个视频，--cover 只处理封面(每分钟)，--codeinfo 处理转码(每小时)';

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
            return $this->processCover($video);
        }
        if ($this->option('codeinfo')) {
            $this->processCodeInfos();
        }
        $this->processCovers();
    }

    public function processCodeInfos()
    {
        //只转码封面成功的
        $videos = Video::whereNotNull('qcvod_fileid')
            ->where('status', 1)
            ->get();
        foreach ($videos as $video) {
            $this->processCodeinfo($video);
        }
    }

    public function processCovers()
    {
        //没有Cover的都可以被处理
        $videos = Video::whereNotNull('qcvod_fileid')
            ->whereNull('cover')
            ->where('status', '>', -1) //-1的有问题的不再重复处理
            ->get();
        foreach ($videos as $video) {
            $this->processCover($video);
        }
    }

    public function processCodeinfo($video)
    {
        $this->info("$video->id $video->title");
        $this->comment("fileid = $video->qcvod_fileid");
        $flag          = $video->syncVodProcessResult();
        $video->status = $flag;
        $video->save();
        if ($flag == 2) {
            $this->info("已转码: $video->path");
            return;
        }

        //旧的视频都还没转码的,提交任务
        if ($flag < 2) {
            $this->comment("开始转码...");
            $video->transCode();
            sleep(5);
        }
    }

    public function processCover($video)
    {
        //尝试获取截图
        if (!$video->cover) {
            $flag = $video->syncVodProcessResult();

            //$flag -1 表示文件有问题
            if (!$flag) {
                $video->status = -1;
                return $video->save();
            }
        }

        //如果还没有截图 就重新执行调用截图接口
        if (!$video->cover) {
            $this->info("$video->id $article->title $video->path");
            $duration = $video->duration > 9 ? 9 : $video->duration;
            QcloudUtils::makeCoverAndSnapshots($video->qcvod_fileid, $duration);
            //真的调用vod:api了，限制下频率
            sleep(5);
        }
    }
}
