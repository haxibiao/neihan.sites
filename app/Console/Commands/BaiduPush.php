<?php

namespace App\Console\Commands;

use App\Movie;
use App\Site;
use Illuminate\Console\Command;

class BaiduPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baidu:push';

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
        $qb = Site::whereNotNull('ziyuan_token');
        $qb->chunkById(100, function ($sites) {
            foreach ($sites as $site) {
                $count = 0;
                Movie::whereStatus(Movie::PUBLISH)->chunkById(100, function ($movies) use ($site, &$count) {
                    foreach ($movies as $movie) {
                        $urls   = [];
                        $domain = $site->domain;
                        foreach ($movies as $movie) {
                            $urls[] = "https://{$domain}" . '/movie/' . $movie->id;
                        }
                        $api    = "http://data.zz.baidu.com/urls?site=https://{$domain}//&token={$site->ziyuan_token}";
                        $result = pushSeoUrl($urls, $api);
                        if ($result) {
                            $result = json_decode($result);
                            if (property_exists($result, 'success')) {
                                $this->info("{$domain}推送成功URL条数:" . optional($result)->success);
                                $this->info("{$domain}剩余可推送URL条数:" . optional($result)->remain);
                                $count += optional($result)->success;
                            } else {
                                $this->error("{$domain}推送失败，配额不够");
                            }
                        }
                    }
                });
            }
        });
    }
}
