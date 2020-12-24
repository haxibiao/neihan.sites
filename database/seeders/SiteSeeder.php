<?php
namespace Database\Seeders;

use App\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::truncate();

        //TKD
        $item = Site::firstOrCreate([
            'domain' => 'diudie.com',
            'name'   => '丢碟',
        ]);
        $item->title = '疯狂看美剧，快乐无极限';
        $item->save();

    }
}
