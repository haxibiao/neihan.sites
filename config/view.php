<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
     */

    'paths'    => [
        //优先读取当前项目的视图
        resource_path('views'),

        //FIXME breeze:install的时候，替换这段过来
        // $vendor_path = "__DIR__ . '/../packages/haxibiao";
        // __DIR__ . '/../packages/haxibiao/breeze/resources/views',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
     */

    'compiled' => realpath(storage_path('framework/views')),

];
