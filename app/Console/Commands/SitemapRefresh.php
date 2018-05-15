<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;

class SitemapRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh sitemap xml ';

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
     * @return mixed
     */
    public function handle()
    {
        $controller = new \App\Http\Controllers\SitemapController();
        $controller->make();
        Cache::put('sitemap', 1, 60 * 24);
    }
}
