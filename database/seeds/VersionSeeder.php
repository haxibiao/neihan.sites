<?php

use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Version::firstOrCreate([
            'name'        => '2.1',
            'url'         => 'http: //dianmoge-1251052432.cos.ap-shanghai.myqcloud.com/dianmoge-release.apk',
            'is_force'    => 1,
            'description' => '完全重构并优化了短视频社交基本功能，增加悬赏问答玩法',
        ]);
    }
}
