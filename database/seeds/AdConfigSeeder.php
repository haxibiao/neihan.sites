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
        $config->value = '5033409';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'tx_appid',
        ]);
        $config->value = '1110143298';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'bd_appid',
        ]);
        $config->value = 'e39305ab';
        $config->save();

        //provider
        $config = AdConfig::firstOrCreate([
            'name' => 'splash_prodiver',
        ]);
        $config->value = '头条';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'feed_prodiver',
        ]);
        $config->value = '头条';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'reward_video_prodiver',
        ]);
        $config->value = '混合';
        $config->save();

        //codeid
        //开屏
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash',
        ]);
        $config->value = '833409512';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash_tencent',
        ]);
        $config->value = '6080991523287551';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash_baidu',
        ]);
        $config->value = '6817082';
        $config->save();

        //信息流
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed',
        ]);
        $config->value = '933409417';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed_tencent',
        ]);
        $config->value = '6090293503884514';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed_baidu',
        ]);
        $config->value = '6817083';
        $config->save();

        //竖屏视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_draw_video',
        ]);
        $config->value = '933409481';
        $config->save();

        //full视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_full_video',
        ]);
        $config->value = '943671835';
        $config->save();

        //激励视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video',
        ]);
        $config->value = '933409954';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video_tencent',
        ]);
        $config->value = '7090090503382538';
        $config->save();
    }
}
