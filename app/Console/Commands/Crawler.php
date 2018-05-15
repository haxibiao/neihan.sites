<?php

namespace App\Console\Commands;

use Goutte\Client;
use Illuminate\Console\Command;

class Crawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:start';

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
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url     = 'https://www.duitang.com/category/?cat=fashion';
        $crawler = $this->client->request('get', $url);

        $categories = $crawler->filter('body .dt-cat-content .dt-sub-cat .dt-tag-list a')->each(function ($node) {
            $category['name'] = $node->html();
            $category['href'] = $node->attr('href');
            return $category;
        });
        // print_r($categories);
        for ($page = 0; $page <= 100; $page++) {
            $per  = 24 * $page;
            $url1 = 'https://www.duitang.com/napi/blog/list/by_filter_id/?include_fields=top_comments%2Cis_root%2Csource_link%2Citem%2Cbuyable%2Croot_id%2Cstatus%2Clike_count%2Csender%2Calbum&filter_id=%E6%97%B6%E5%B0%9A%E6%90%AD%E9%85%8D_%E6%90%AD%E9%85%8D%E8%BE%BE%E4%BA%BA&start=' . $per . '&_=15180756646' . $page;
            $jsn  = file_get_contents($url1);
            $com  = json_decode($jsn);
            $this->info($com->data->object_list[0]->msg);
        }
        dd('1111');
        foreach ($categories as $category) {
            $crawler1 = $this->client->request('get', $category['href']);

            $articles = $crawler->filter('.woo-pcont .woo .j .mbpho .a')->each(function ($node) {
                if (strpos($node->attr('href'), 'blog')) {

                    $article['href']  = $node->first()->attr('href');
                    $article['title'] = $node->children()->first()->filter('img')->attr('alt');
                    return $article;
                }
            });
            dd($articles);
        }
    }
}
