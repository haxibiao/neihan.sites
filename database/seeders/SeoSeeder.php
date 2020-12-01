<?php
namespace Database\Seeders;

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
        Seo::truncate();

        //TKD
        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'title',
        ]);
        $item->value = '疯狂看美剧，快乐无极限';
        $item->save();
        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'keywrods',
        ]);
        $item->value = '精选美剧，告别剧荒，播放流畅，更新迅速，精彩短视频，抖音精彩电影剪辑合集';
        $item->save();

        $item = Seo::firstOrCreate([
            'group' => 'TKD',
            'name'  => 'description',
        ]);
        $item->value = '行尸走肉、西部世界、天赋异禀、黑袍纠察队、邪恶力量、暮光之城等精彩影视大片一网打尽，小编为您精心搜集了所有经典爆款、时下热门、最新上映的美剧资源，资源齐全更新快，全部支持免费在线观影！';
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
