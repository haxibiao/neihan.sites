<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Performance\Config;
use Performance\Performance;

class TestController extends Controller
{
    public function __construct()
    {
        Config::setQueryLog(true);
        Config::setPresenter('web');
    }

    public function perf()
    {
        // Set measure point
        Performance::point('基本读操作');

        Performance::point('Multiple point 1', true);
        Performance::point('Multiple point 2', true);
// Run test code
        \App\Article::where('status', '>', 0)->take(10)->get();
        Performance::finish();

        Performance::point('基本写操作');

// Run test code
        \App\Article::where('status', '>', 0)->take(10)->get();
        Performance::finish();

        Performance::point('基本Job操作');

// Run test code
        \App\Article::where('status', '>', 0)->take(10)->get();
        Performance::finish();

// Finish all tasks and show test results
        Performance::results();
    }
}
