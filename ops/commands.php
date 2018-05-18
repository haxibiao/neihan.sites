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
	$cmd = new \tools\commands\FixData($this);
    $cmd->run();
})->describe('fix data ...');



