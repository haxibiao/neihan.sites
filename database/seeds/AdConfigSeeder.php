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
            'name' => 'banner_prodiver',
        ]);
        $config->value = '头条';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'feed_prodiver',
        ]);
        $config->value = '头条';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'draw_video_prodiver',
        ]);
        $config->value = '头条';
        $config->save();

        //默认激励视频 provider
        $config = AdConfig::firstOrCreate([
            'name' => 'reward_video_prodiver',
        ]);
        $config->value = '头条';
        $config->save();

        //备用激励视频 provider
        $config = AdConfig::firstOrCreate([
            'name' => 'reward_video_prodiver2',
        ]);
        $config->value = '腾讯';
        $config->save();

        //codeid
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash',
        ]);
        $config->value = '817576238';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_banner',
        ]);
        $config->value = '917576007';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed',
        ]);
        $config->value = '917576575';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_draw_video',
        ]);
        $config->value = '917576134';
        $config->save();

        //默认激励视频 codeid
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video',
        ]);
        $config->value = '917576640';
        $config->save();
        //备用激励视频 codeid
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video2',
        ]);
        $config->value = '7080992436522638';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_full_video',
        ]);
        $config->value = '917576981';
        $config->save();

    }
}
