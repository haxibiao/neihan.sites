<?php

use App\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //正式上线后，不要再清空，要保留运营更新的 图文...
        DB::table('tasks')->truncate();

        Task::firstOrCreate([
            'name'           => '完善头像',
            'status'         => Task::ENABLE,
            'details'        => '更改账号头像',
            'group'          => '新人任务',
            'type'           => Task::NEW_USER_TASK,
            'reward'         => array("gold" => "10"),
            'resolve'        => array("method"=> "checkUserIsUpdateAvatar", "router"=>"editInformation", "submit_name"=>"去修改"),
            'review_flow_id' => 5,
        ]);

        Task::firstOrCreate([
            'name'           => '视频发布满15个',
            'group'          => '每日任务',
            'status'         => Task::ENABLE,
            'details'        => '去抖音采集15个视频即可领取奖励',
            'type'           => Task::CUSTOM_TASK,
            'reward'         => array("gold" => "10"),
            'resolve'        => array('limit' => '15', 'router' => '', 'method' => 'publicArticleTask','submit_name'=>'去发布'),
            'review_flow_id' => 3,
            'max_count'      => 15,
        ]);

        Task::firstOrCreate([
            'name'           => '绑定手机号',
            'status'         => Task::ENABLE,
            'details'        => '绑定手机号',
            'group'          => '新人任务',
            'type'           => Task::NEW_USER_TASK,
            'reward'         => array("gold" => "50"),
            'resolve'        => array('method' => 'checkUserIsUpdatePassAndPhone', 'router' => 'accountBinding','submit_name'=>'去绑定'),
            'review_flow_id' => 6,
        ]);

        Task::firstOrCreate([
            'name'           => '修改性别和生日',
            'status'         => Task::ENABLE,
            'details'        => '修改性别和生日',
            'group'          => '新人任务',
            'type'           => Task::NEW_USER_TASK,
            'reward'         => array("gold" => "10"),
            'resolve'        => array('method' => 'checkUserIsUpdateGenderAndBirthday', 'router' => 'editInformation','submit_name'=>'去修改'),
            'review_flow_id' => 7,
        ]);

        Task::firstOrCreate([
            'name'           => '视频采集悬浮球',
            'details'        => '打开视频采集悬浮球',
            'group'          => '新人任务',
            'type'           => Task::CUSTOM_TASK,
            'status'         => Task::DISABLE,
            'resolve'        => array('submit_name'=>'去打开'),
            'review_flow_id' => 0,
        ]);

        Task::firstOrCreate([
            'name'           => '应用商店好评',
            'details'        => '应用商店好评',
            'group'          => '新人任务',
            'type'           => Task::CUSTOM_TASK,
            'status'         => Task::DISABLE,
            'resolve'        => array('method' => '', 'router' => 'ToComment','submit_name'=>'去好评'),
            'review_flow_id' => 8,
        ]);

        Task::firstOrCreate([
            'name'           => '有趣小视频',
            'group'          => '每日任务',
            'details'        => '看视频有机会获得健康分哦',
            'type'           => Task::DAILY_TASK,
            'status'         => Task::ENABLE,
            'reward'         => array("gold" => "10", "contribute" => "5"),
            'resolve'        => array('method' => '', 'router' => 'MotivationalVideo','submit_name'=>'去观看'),
            'review_flow_id' => 4,
            'max_count'      => 10,
        ]);

        Task::firstOrCreate([
            'name'           => '回答问题X2',
            'group'          => '每日任务',
            'details'        => '回答问题有机会获得奖励哦',
            'type'           => Task::DAILY_TASK,
            'status'         => Task::ENABLE,
            'reward'         => array("gold" => "30"),
            'resolve'        => array('submit_name'=>'去回答'),
            'review_flow_id' => 12,
            'max_count'      => 2,
        ]);

        Task::firstOrCreate([
            'name'           => '发布问题X2',
            'group'          => '每日任务',
            'details'        => '发布问题有机会获得奖励哦',
            'type'           => Task::DAILY_TASK,
            'status'         => Task::ENABLE,
            'reward'         => array("gold" => "30"),
            'resolve'        => array('submit_name'=>'去提问'),
            'review_flow_id' => 13,
            'max_count'      => 2,
        ]);
    }
}
