<?php

use App\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('tasks')->truncate();

        // Task::where('name','like','%完善头像%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/changeAvatar.png'
        // ]);

        // Task::where('name','like','%修改性别和生日%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/changeGender.png'
        // ]);

        // Task::where('name','like','%视频发布%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/publish.png'
        // ]);

        // Task::where('name','like','%喝水赚钱%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/drinkWater.png'
        // ]);

        // Task::where('name','like','%应用商店好评%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/NiceComment.png'
        // ]);
        // Task::where('name','like','%看视频赚钱%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/watchVideo.png'
        // ]);

        // Task::where('name','like','%睡觉赚钱%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/sleep.png'
        // ]);
        // Task::where('name','like','%绑定手机号%')->first()->update([
        //     'icon' => 'https://dongdezhuan-1254284941.cos.ap-guangzhou.myqcloud.com/taskImages/bindPhoneNumber.png'
        // ]);

        // Task::where('name', 'SleepMorning')->first()->update([
        //     'reward' => array("gold" => "15", 'contribute' => '1'),
        // ]);

        // Task::where('name', 'like', '%睡觉赚钱%')->first()->update([
        //     'reward' => array("gold" => "15", 'contribute' => '1'),
        // ]);

        // $DrinkWater_all = Task::where('name', '喝水赚钱')->first();
        // $DrinkWater_all->update([
        //     'resolve' => array('method' => 'drinkWater', 'router' => 'GoDrinkWater', 'task_en' => 'DrinkWaterAll', 'limit' => 8),
        // ]);
        //
        $DrinkWaterTasks = Task::where('name', 'DrinkWater')->get();

        foreach ($DrinkWaterTasks as $DrinkWaterTask) {
            $DrinkWaterTask->update([
                'parent_task' => 12,
                'resolve'     => array('task_en' => 'DrinkWater', 'task_undone' => '未喝', 'task_done' => '以完成', 'task_failed' => '没开始', 'task_review' => '进行中', 'task_reach' => '以喝'),
            ]);
        }

        // Task::where('name', '睡觉赚钱')->first()->update(
        //     [
        //         'type'    => 2,
        //         'resolve' => array("method" => "sleep", "router" => "GoSleep", 'task_en' => 'SleepAll', 'limit' => 50),
        //     ]
        // );

        Task::where('name', '应用商店好评')->first()->update(
            [
                'resolve' => array("method" => "toPraise", "router" => "ToPraise", 'task_en' => 'Praise'),
            ]
        );

        // Task::where('name', '喝水赚钱')->first()->update(
        //     [
        //         'type' => 2,
        //     ]
        // );

        Task::where('name', '看视频赚钱')->first()->update(
            [
                'type'    => 2,
                'resolve' => array("method" => "rewardVideo", "router" => "MotivationalVideo", 'limit' => 30, 'task_en' => 'RewardVideo'),
            ]
        );

        Task::where('name', 'SleepMorning')->first()->update(
            [
                'name'    => '起床',
                'details' => '别睡了起来嗨',
                'resolve' => array('minutes' => '15', 'task_en' => 'Wake'),
            ]
        );

        // Task::where('name', '起床卡')->first()->update(
        //     ['details' => '别睡了起来嗨']
        // );

        Task::where('name', 'SleepNight')->first()->update(
            [
                'name'    => '睡觉',
                'reward'  => null,
                'resolve' => array('minutes' => '15', 'task_en' => 'Sleep'),
            ]
        );

        // Task::where('name', '睡觉卡')->first()->update(
        //     [
        //         'name'    => '睡觉卡',
        //         'reward'  => null,
        //         'resolve' => array('minutes' => '15', 'task_en' => 'Sleep'),
        //     ]
        // );

        //    Task::firstOrCreate([
        //        'name'    => 'DrinkWaterAll',
        //        'status'  => Task::ENABLE,
        //        'details' => '喝水赚钱总任务打卡',
        //        'type'    => Task::TIME_TASK,
        //        'reward'  => array("gold" => "100"),
        //    ]);
        //
        //    Task::firstOrCreate([
        //        'name'     => 'SleepMorning',
        //        'status'   => Task::ENABLE,
        //        'details'  => '起床卡凌晨0点到12点',
        //        'type'     => Task::TIME_TASK,
        //        'reward'   => array("gold" => "15", 'contribute' => '1'),
        //        'resolve'  => array('minutes' => '15'),
        //        'start_at' => '2019-12-03 00:00:00',
        //        'end_at'   => '2019-12-03 11:59:59',
        //    ]);

        //    Task::firstOrCreate([
        //        'name'     => 'SleepNight',
        //        'status'   => Task::ENABLE,
        //        'details'  => '睡觉卡12点到24点',
        //        'type'     => Task::TIME_TASK,
        //        'reward'   => array("gold" => "15"),
        //        'resolve'  => array('minutes' => '15'),
        //        'start_at' => '2019-12-03 12:00:00',
        //        'end_at'   => '2019-12-03 23:59:59',
        //    ]);
        //

        //
        // Task::firstOrCreate([
        //     'name'    => '睡觉赚钱',
        //     'status'  => Task::ENABLE,
        //     'details' => '睡觉赚钱',
        //     'type'    => Task::CUSTOM_TASK,
        //     'reward'  => array("gold" => "15", 'contribute' => '1'),
        //     'resolve' => array('method' => 'sleep', 'router' => 'GoSleep'),
        // ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '完善头像',
        //            'status'  => Task::ENABLE,
        //            'details' => '前往个人信息页面完善头像',
        //            'type'    => Task::NEW_USER_TASK,
        //            'reward'  => array("gold" => "10"),
        //            'resolve' => array('method' => 'checkUserIsUpdateAvatar', 'router' => 'editInformation'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '今日视频发布满15个',
        //            'status'  => Task::ENABLE,
        //            'details' => '视频发布满15个',
        //            'type'    => Task::DAILY_TASK,
        //            'reward'  => array("gold" => "10", 'contribute' => '1'),
        //            'resolve' => array('limit' => '15', 'router' => '', 'method' => 'publicArticleTask'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '绑定手机号',
        //            'status'  => Task::ENABLE,
        //            'details' => '绑定手机号',
        //            'type'    => Task::NEW_USER_TASK,
        //            'reward'  => array("gold" => "50"),
        //            'resolve' => array('method' => 'checkUserIsUpdatePassAndPhone', 'router' => 'accountBinding'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '修改性别和生日',
        //            'status'  => Task::ENABLE,
        //            'details' => '修改性别和生日',
        //            'type'    => Task::NEW_USER_TASK,
        //            'reward'  => array("gold" => "10"),
        //            'resolve' => array('method' => 'checkUserIsUpdateGenderAndBirthday', 'router' => 'editInformation'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '视频采集悬浮球',
        //            'details' => '打开视频采集悬浮球',
        //            'type'    => Task::CUSTOM_TASK,
        //            'status'  => Task::DISABLE,
        //            'resolve' => array('method' => '', 'router' => 'GoDrinkWater'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '应用商店好评',
        //            'details' => '应用商店好评',
        //            'type'    => Task::CUSTOM_TASK,
        //            'status'  => Task::DISABLE,
        //            'resolve' => array('method' => '', 'router' => 'ToComment'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '看视频赚钱',
        //            'details' => '看视频有机会获得贡献值哦',
        //            'type'    => Task::CUSTOM_TASK,
        //            'status'  => Task::ENABLE,
        //            'reward'  => array("gold" => "7"),
        //            'resolve' => array('method' => '', 'router' => 'MotivationalVideo'),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '观看采集视频教程',
        //            'details' => '观看采集视频教程',
        //            'type'    => Task::NEW_USER_TASK,
        //            'status'  => Task::ENABLE,
        //            'reward'  => array("gold" => "150"),
        //        ]);
        //
        //        Task::firstOrCreate([
        //            'name'    => '观看新手视频教程',
        //            'details' => '观看新手视频教程',
        //            'type'    => Task::NEW_USER_TASK,
        //            'status'  => Task::ENABLE,
        //            'reward'  => array("gold" => "150"),
        //        ]);
    }
}
