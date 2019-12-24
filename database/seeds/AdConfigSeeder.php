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
        $config->value = '';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'tx_appid',
        ]);
        $config->value = '';
        $config->save();

        $config = AdConfig::firstOrCreate([
            'name' => 'bd_appid',
        ]);
        $config->value = '';
        $config->save();

        //provider
        $config = AdConfig::firstOrCreate([
            'name' => 'splash_prodiver',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'banner_prodiver',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'feed_prodiver',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'draw_video_prodiver',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'reward_video_prodiver',
        ]);
        $config->value = '';
        $config->save();

        //codeid
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_splash',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_banner',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_feed',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_draw_video',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_reward_video',
        ]);
        $config->value = '';
        $config->save();
        $config = AdConfig::firstOrCreate([
            'name' => 'codeid_full_video',
        ]);
        $config->value = '';
        $config->save();

    }
}
