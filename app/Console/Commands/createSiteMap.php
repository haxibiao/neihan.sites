<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createSiteMap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client   = new Client();
        $url = "https://diudie.com/sitemap/all";
        //重新创建一波sitemap 
        if (file_exists(public_path("sitemap/google.xml"))) {
            Storage::disk('sitemap')->delete("google.xml");
            //访问一下路由
            $client->request('GET', "{$url}/google");
        }

        if (file_exists(public_path("sougou.xml"))) {
            Storage::disk('sitemap')->delete("sougou.xml");
            $client->request('GET', "{$url}/sougou");
        }

        if (file_exists(public_path("sitemap/360.xml"))) {
            Storage::disk('sitemap')->delete("360.xml");
            $client->request('GET', "{$url}/360");
        }

        if (file_exists(public_path("sitemap/baidu.xml"))) {
            Storage::disk('sitemap')->delete("baidu.xml");
            $client->request('GET', "{$url}/baidu");
        }
    }
}
