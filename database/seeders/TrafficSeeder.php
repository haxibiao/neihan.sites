<?php
namespace Database\Seeders;

use Haxibiao\Cms\Model\Traffic;
use Illuminate\Database\Seeder;

class TrafficSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Traffic::truncate();

        //初始化2个蜘蛛抓取记录
        Traffic::create([
            'url'     => 'http://l.diudie.com/movie',
            'domain'  => 'diudie.com',
            'bot'     => 'baiduSpider',
            'referer' => '',
            'engine'  => '',
        ]);
        Traffic::create([
            'url'     => 'http://l.diudie.com/movie',
            'domain'  => 'diudie.com',
            'bot'     => 'googleSpider',
            'referer' => '',
            'engine'  => '',
        ]);

    }
}
