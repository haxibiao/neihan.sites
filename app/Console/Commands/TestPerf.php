<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Performance\Performance;
use Performance\Config;

class TestPerf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:perf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test performance';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        Config::setQueryLog(true);
        Config::setPresenter('console');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Set measure point
        Performance::point('基本读操作');

        Performance::point('Multiple point 1', true);
        Performance::point('Multiple point 2', true);
        // Run test code
        \App\Article::where('status','>',0)->take(10)->get();
        Performance::finish();

        Performance::point('基本写操作');


        // Run test code
        \App\Article::where('status','>',0)->take(10)->get();
        Performance::finish();

        Performance::point('基本Job操作');


        // Run test code
        \App\Article::where('status','>',0)->take(10)->get();
        Performance::finish();

        // Finish all tasks and show test results
        Performance::results();
    }
}
