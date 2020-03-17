<?php

namespace App\Console\Commands;

use App\Contribute;
use App\User;
use Illuminate\Console\Command;

class CheckUserRewardVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:rewardvideo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查看激励视频次数不正常的用户';

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
        //
    }

    public function checkUser()
    {
        $userIds               = Contribute::whereDate('created_at', today())->select('user_id');
        $contributeMaxDayCount = 30;
        foreach ($userIds as $id) {
            $user = User::find($id);
            // 今日看激励视频的次数
            $count = Contribute::getCountByType(Contribute::REWARD_VIDEO_CONTRIBUTED_TYPE, $user);
            if ($count > $contributeMaxDayCount) {
                $user->update(['status' => User::STATUS_FREEZE]);
                $this->info("user id {$user->id} , 看激励视频次数异常封号");
            }
        }
    }
}
