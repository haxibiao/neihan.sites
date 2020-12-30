<?php

namespace App\Console\Commands;

use App\Site;
use Illuminate\Console\Command;

class BaiduInclude extends Command{

    protected $signature = 'baidu:include';

    protected $description = '保存每个站近30天的百度索引量';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sites = Site::get();
        foreach ($sites as $site) {
            $json = $site->json;
            if($json){
                $include = $json['baidu'];
                unset($include[today()->subday(30)->toDateString()]);
                $include[today()->toDateString()] = baidu_include_check($site->domain)[0]['收录'];
                $json['baidu'] = $include;
                $site->json = $json;
            }else{
                $include = [];
                for($i=29;$i>0;$i--){
                    $include[today()->subday($i)->toDateString()] = 0;
                }
                // 
                $include[today()->toDateString()] = baidu_include_check($site->domain)[0]['收录'];
                $json['baidu'] = $include;
                $site->json = $json;
            }
            $site->save();
        }
    }
}