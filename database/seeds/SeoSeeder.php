<?php

use App\Seo;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Seo::whereNotNull('name')->delete();

        //TKD
        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'title',
        ]);
        $item->value = '随时分享你的快乐瞬间';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'keywrods',
        ]);
        $item->value = '幽默，搞笑，段子，笑话大全，开心一刻，冷笑话, 内涵知识';
        $item->save();

        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'description',
        ]);
        $item->value = '点默阁-专注段子和知识搜索的社交平台';
        $item->save();

        //备案
        $item = Seo::firstOrCreate([
            'group' => '备案',
            'name'  => 'copyright',
        ]);
        $item->value = 'Copyright ©2019 三河市哈希表计算机技术有限公司 All Rights Reserved';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => '备案',
            'name'  => '公司主体',
        ]);
        $item->value = '三河市哈希表计算机技术有限公司';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => '备案',
            'name'  => '备案号',
        ]);
        $item->value = '冀ICP备17022765号';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => '备案',
            'name'  => '公安网备号',
        ]);
        $item->value = '冀公安网备 13108202000425号';
        $item->save();

        //统计
        $item = Seo::firstOrCreate([
            'group' => '统计',
            'name'  => 'matomo',
        ]);
        $item->value = '';
        $item->save();

        //百度
        $item = Seo::firstOrCreate([
            'group' => '百度',
            'name'  => 'meta',
        ]);
        $item->value = '';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => '百度',
            'name'  => 'push',
        ]);
        $item->value = '';
        $item->save();

    }
}
