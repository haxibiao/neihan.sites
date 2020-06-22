<?php

namespace App\Console\Commands;

use haxibiao\helpers\QcloudUtils;
use Illuminate\Console\Command;

class VodApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vod:api {method} {--fileid=} {--taskid=} {--video_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'call PullEvent and ConfirmEvent for qcloud vod...';

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
        if ($this->argument('method') == 'clearEvents') {
            return $this->clearEvents();
        }
        if ($this->argument('method') == 'pullEvents') {
            $res = QcloudUtils::pullEvents();
            dd($res);
            return;
        }
        if ($this->argument('method') == 'getTaskList') {
            $res = QcloudUtils::getTaskList();
            dd($res);
            return;
        }
        if ($this->argument('method') == 'getTaskInfo') {
            if (empty($this->option('taskid'))) {
                return $this->error('--taskid cannot be empty');
            }
            $res = QcloudUtils::getTaskInfo($this->option('taskid'));
            dd($res);
            return;
        }

        $fileid = $this->option('fileid');
        if ($this->option('video_id')) {
            $video  = \App\Video::findOrFail($this->option('video_id'));
            $fileid = $video->qcvod_fileid;
        }

        if (!$fileid) {
            return $this->error('--fileid cannot be empty');
        }

        if ($fileAction = $this->argument('method')) {
            $res = QcloudUtils::$fileAction($fileid);
            dd($res);
        }
    }

    public function clearEvents()
    {
        $res        = QcloudUtils::pullEvents();
        $msgHandles = [];
        if (is_array($res['eventList'])) {
            foreach ($res['eventList'] as $event) {
                $msgHandles[] = $event['msgHandle'];
            }
            $this->info("清理掉的事件记录数:" . count($msgHandles));
            $confirm_res = QcloudUtils::confirmEvents($msgHandles);
            dd($confirm_res);
        } else {
            $this->error('没有拉取到事件记录');
        }
    }
}
