<?php

namespace App\Console\Commands;

use App\Helpers\QcloudUtils;
use Illuminate\Console\Command;
use Vod\VodApi;

class VodEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vod:event';

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
        VodApi::initConf(config('qcloudcos.secret_id'), config('qcloudcos.secret_key'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $res = QcloudUtils::getTaskList();
        // dd($res);

        // $res = QcloudUtils::processVodFile("5285890780692143813");
        // dd($res);

        // $res = QcloudUtils::takeVodSnapshots("5285890780696151817");
        // $this->info(json_encode($res));

        $res = QcloudUtils::pullEvents();
        dd($res);

        // $this->confirmEvents();
    }

    public function confirmEvents()
    {
        $res        = QcloudUtils::pullEvents();
        $msgHandles = [];
        if (is_array($res['eventList'])) {
            foreach ($res['eventList'] as $event) {
                $msgHandles[] = $event['msgHandle'];
            }
            $confirm_res = QcloudUtils::confirmEvents($msgHandles);
            dd($confirm_res);
        } else {
            $this->error('没有拉取到事件记录');
        }
    }
}
