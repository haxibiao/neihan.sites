<?php
return [
    'secret_id'        => env('LIVE_SECRET_ID', 'AKID50fqHTh7kAPPJCEEMPcfUDqKYWP4oOJT'),
    'secret_key'       => env('LIVE_SECRET_KEY'),

    //推流key
    'live_key'         => env('LIVE_KEY'),
    //云直播机房地区
    'location'         => env('LIVE_LOCATION', "ap-guangzhou"),

    //app名称, 意思一个域名下可以配置多个直播APP
    'app_name'         => 'live',
    //推流URL
    'live_push_domain' => env('LIVE_PUSH_DOMAIN'),
    //拉流地址
    'live_pull_domain' => env('LIVE_PULL_DOMAIN'),
];
