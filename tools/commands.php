<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('fix:data {operation} {--force}', function ($operation) {
	$commander = new \tools\commands\FixData($this);	
	$commander->force = $this->option('force');
    if($operation == "tags") {
    	$commander->fix_tags();
    }
    if($operation == "comments") {
    	$commander->fix_tags();
    }
    if($operation == "traffic") {
    	$commander->fix_tags();
    }
    if($operation == "articles") {
    	$commander->fix_tags();
    }
    if($operation == "images") {
    	$commander->fix_tags();
    }
    if($operation == "videos") {
    	$commander->fix_tags();
    }
    if($operation == "categories") {
    	$commander->fix_tags();
    }
    if($operation == "users") {
    	$commander->fix_tags();
    }
})->describe('fix data ...');



