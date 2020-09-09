<?php

use App\AdConfig;
use Illuminate\Database\Seeder;

class AdConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        AdConfig::truncate();

        $config = AdConfig::firstOrCreate([
            'name' => 'tt_appid',
        ]);
        $config->value = '5099588';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash',
        ]);
        $config->value = '887368877';
        $config->save();

        //竖屏视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_draw_video',
        ]);
        $config->value = '945418808';
        $config->save();

        //信息流
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed',
        ]);
        $config->value = '945418809';
        $config->save();

        //激励视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video',
        ]);
        $config->value = '945448682';
        $config->save();

        //全屏视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_full_video',
        ]);
        $config->value = '945448691';
        $config->save();

        //provider
        $config = AdConfig::firstOrCreate([
            'name' => 'splash_provider',
        ]);
        $config->value = '头条';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'feed_provider',
        ]);
        $config->value = '头条';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'reward_video_provider',
        ]);
        $config->value = '头条';
        $config->save();


    }
}
