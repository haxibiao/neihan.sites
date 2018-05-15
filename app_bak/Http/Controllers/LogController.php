<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function debug()
    {
        $env_file = __DIR__ . '/../../../.env';
        $env = file_get_contents($env_file);
        if (str_contains($env, 'APP_DEBUG=true')) {
            $env = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $env);
            file_put_contents($env_file, $env);
            return '<h1 style="color:green"> Now debug disabled ! </h1>';
        } else {
            $env = str_replace('APP_DEBUG=false', 'APP_DEBUG=true', $env);
            file_put_contents($env_file, $env);
            return '<h1 style="color:red"> Now debug enabled ! </h1>';
        }

    }

    public function logShow()
    {
        $today_log = __DIR__ . '/../../../' . 'storage/logs/laravel-' . date("Y-m-d") . '.log';
        if (file_exists($today_log)) {
            $error_log_html = str_replace("\n", "<br/>", file_get_contents($today_log));
            $error_log_html = str_replace("production.ERROR", "<span style='color:red'>production.ERROR</span>", $error_log_html);
            $error_log_html = str_replace("prod.ERROR", "<span style='color:red'>prod.ERROR</span>", $error_log_html);
            $error_log_html = str_replace("ErrorException", "<span style='color:red'>ErrorException</span>", $error_log_html);
            return $error_log_html;
        } else {
            return '<h1 style="color:green"> Congratulations! Everything is fine! </h1>';
        }

    }

    public function logClear()
    {
        $today_log = __DIR__ . '/../../../' . 'storage/logs/laravel-' . date("Y-m-d") . '.log';
        if (file_exists($today_log)) {
            unlink($today_log);
            return 'clear logs of today done!';
        } else {
            return '<h1 style="color:green"> No log file! </h1>';
        }
    }
}
