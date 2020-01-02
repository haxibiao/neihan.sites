<?php

use App\Aso;
use Illuminate\Database\Seeder;

class AsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Aso::whereNotNull('name')->delete();
        //ASO
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '安卓地址',
        // ]);
        // $item->value = 'https://dianmoge-1251052432.cossh.myqcloud.com/dianmoge-release.apk';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '苹果地址',
        // ]);
        // $item->value = 'https://itunes.apple.com/cn/app/%E7%82%B9%E5%A2%A8%E9%96%A3/id1434767781?mt=8';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍1图',
        // ]);
        // $item->value = '/images/app/dianmoge1.png';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍1标题',
        // ]);
        // $item->value = '实时热搜、搞笑视频、内涵段子、原创文学';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍1文字',
        // ]);
        // $item->value = '你是否想了解最新的热门搜索内容？你是否想观看最搞笑的段子和视频？</br>你是否想分享你的原创文学成为作者大大？点墨阁 app一网打尽！你想要我们都有!';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍2图',
        // ]);
        // $item->value = '/images/app/dianmoge2.png';
        // $item->save();
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍2标题',
        // ]);
        // $item->value = '海量短视频、个性化推送';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍2文字',
        // ]);
        // $item->value = '精彩爆笑的段子，脑洞大开的视频。在这里你可以分享生活趣事、上传游戏精彩瞬间、秀出你的风采、传递开心。';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍3图',
        // ]);
        // $item->value = '/images/app/dianmoge3.png';
        // $item->save();
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍3标题',
        // ]);
        // $item->value = '优质内容、独家创造';
        // $item->save();
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍3文字',
        // ]);
        // $item->value = '在这里你可以创造属于你的特色专题内容。记录你的日常有趣瞬间、与共同爱好的玩家分享快乐，让你不再一个人游戏、给你带来不同寻常的邂逅。';
        // $item->save();

        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍4图',
        // ]);
        // $item->value = '/images/app/dianmoge4.png';
        // $item->save();
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍4标题',
        // ]);
        // $item->value = '热门游戏交流社区';
        // $item->save();
        // $item = Aso::firstOrCreate([
        //     'group' => '下载页',
        //     'name'  => '功能介绍4文字',
        // ]);
        // $item->value = '与大神零距离互动，为信仰站队。</br>电竞大神、游戏迷妹的聚集地，创造属于你的游戏世界。';
        // $item->save();

        $item = Aso::firstOrCreate([
            'group' => '下载页',
            'name'  => 'logo',
        ]);
        $item->value = "/logo/" . env('APP_NAME') . '.com.small.png';

        $item->save();

    }
}
