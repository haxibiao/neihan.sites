<?php

use App\Version;
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
        //返回过多的旧版本信息无意义...
        Version::truncate();

        Version::firstOrCreate([
            'name'        => '2.82',
            'url'         => 'http://' . env('COS_DOMAIN') . '/' . env('APP_NAME') . '-release.apk',
            'is_force'    => 1,
            'os'          => 'Android',
            'type'        => 1, //正式
            'size'        => '35554432', //大约35M,不需要太精准
            'package'     => 'com.' . env('APP_NAME'),
            'status'      => Version::RUNNING,
            'description' => "1.添加看激励视频时间\n2.间隔修复睡觉打卡判断机制问题\n3.修复Android9.0的Http网络阻断图片加载不了问题",
        ]);

    }
}
