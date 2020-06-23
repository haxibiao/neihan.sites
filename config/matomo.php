<?php

return [
    //总开关,可以暂时关闭
    'on'         => env('MATOMO_ON', true),

    //是否借用swoole的proxy实现tcp+bulk发送
    'use_swoole' => env('MATOMO_USE_SWOOLE', false),
    'proxy_port' => env('MATOMO_PROXY_PORT', 9502),
    'token_auth' => env('MATOMO_TOKEN_AUTH'),

    //后端事件统计的site_id
    'matomo_id'  => env('MATOMO_ID'),
    //后端事件统计查看的matomo url
    'matomo_url' => env('MATOMO_URL'),

    //APP事件统计的site_id 前端可以直接上报了,基本不借道后端了
    'app_id'     => env('MATOMO_APP_ID', env('MATOMO_ID')),
    //APP事件统计查看的matomo url
    'app_url'    => env('MATOMO_APP_URL', env('MATOMO_URL')),

    //Web事件统计的site_id
    'web_id'     => env('MATOMO_WEB_ID', env('MATOMO_ID')),
    //Web事件统计查看的matomo url
    'web_url'    => env('MATOMO_WEB_URL', env('MATOMO_URL')),
];
