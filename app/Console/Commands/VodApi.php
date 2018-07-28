<?php

namespace App\Console\Commands;

use App\Helpers\QcloudUtils;
use Illuminate\Console\Command;

class VodApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vod:api {method} {--fileid=}';

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

        if (!in_array($this->argument('method'), ['pullEvents', 'getTaskList']) &&
            (empty($this->option('fileid')))) {
            return $this->error('--fileid cannot be empty');
        }

        if ($fileAction = $this->argument('method')) {
            $res = QcloudUtils::$fileAction($this->option('fileid'));
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
