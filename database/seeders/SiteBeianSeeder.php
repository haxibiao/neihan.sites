<?php
namespace Database\Seeders;

use App\Site;
use Illuminate\Database\Seeder;

/**
 * 一批带备案的域名站群
 */
class SiteBeianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (neihan_beian_domains() as $domain => $name) {
            $item = Site::firstOrCreate([
                'domain' => $domain,
                'name'   => $name,
            ]);
            // $item->title        = '疯狂看美剧，快乐无极限';
            // $item->keywords     = '在线美剧，在线韩剧，经典美剧，怀旧港剧，高清日剧';
            // $item->description  = $name . ' ' . $domain . ' 是一个可以免费看全网影视大全的内涵电影网站';
            // $item->ziyuan_token = 'AWrriJorO5KMKgyj'; //曾聪的diudie
            // $item->owner = '曾聪';
            $item->icp         = $item->icp ? $item->icp : '本站有备案，需要填写ICP备案号';
            $item->verify_meta = ''; //seed 清空被hack的js
            $item->footer_js   = ''; //seed 清空被hack的js
            $item->active      = true;
            $item->save();
        }
    }
}
