<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 默认的磁盘
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
     */

    'default' => env('FILESYSTEM_DRIVER', 'public'),

    /*
    |--------------------------------------------------------------------------
    | 默认的云磁盘
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
     */

    'cloud'   => env('FILESYSTEM_CLOUD', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
     */

    'disks'   => [

        'local'  => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('LOCAL_APP_URL', 'http:://l.' . env('APP_NAME') . '.com') . '/storage',
            'visibility' => 'public',
        ],

        //腾讯COSV5
        'cosv5'  => [
            'driver'          => 'cosv5',
            'region'          => env('COS_REGION', 'ap-guangzhou'),
            'credentials'     => [
                'appId'     => env('COS_APP_ID'),
                'secretId'  => env('COS_SECRET_ID'),
                'secretKey' => env('COS_SECRET_KEY'),
            ],
            'timeout'         => env('COS_TIMEOUT', 60),
            'connect_timeout' => env('COS_CONNECT_TIMEOUT', 60),
            'bucket'          => env('COS_BUCKET'),
            'cdn'             => "http://" . env('COS_DOMAIN'),
            'scheme'          => env('COS_SCHEME', 'http'),
            'read_from_cdn'   => env('COS_READ_FROM_CDN', false),
            'cdn_key'         => env('COS_CDN_KEY'),
            'encrypt'         => env('COS_ENCRYPT', false),
            'disable_asserts' => true,
        ],

        's3'     => [
            'driver' => 's3',
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

    ],

];
