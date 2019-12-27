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
        $config = AdConfig::firstOrCreate([
            'name' => 'tt_appid',
        ]);
        $config->value = '5017576';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'tx_appid',
        ]);
        $config->value = '1110085230';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'bd_appid',
        ]);
        $config->value = 'eb720e8a';
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
        $config->value = '头条';
        $config->save();

        //codeid
        //开屏
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash',
        ]);
        $config->value = '817576238';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash_tencent',
        ]);
        $config->value = '5090095206703228';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash_baidu',
        ]);
        $config->value = '6817072';
        $config->save();

        //信息流
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed',
        ]);
        $config->value = '917576575';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed_tencent',
        ]);
        $config->value = '2000997521990326';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed_baidu',
        ]);
        $config->value = '6817074';
        $config->save();

        //竖屏视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_draw_video',
        ]);
        $config->value = '917576134';
        $config->save();

        //激励视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video',
        ]);
        $config->value = '917576640';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video_tencent',
        ]);
        $config->value = '7080992436522638';
        $config->save();

        //全屏视频
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_full_video',
        ]);
        $config->value = '917576981';
        $config->save();

    }
}
