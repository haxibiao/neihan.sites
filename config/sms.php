<?php

return [
    /*
    |--------------------------------------------------------------------------
    | HTTP 请求的超时时间
    |--------------------------------------------------------------------------
    |
    | 设置 HTTP 请求超时时间，单位为「秒」。可以为 int 或者 float。
    |
     */
    'timeout'  => env('SMS_TIMEOUT', 5.0),

    /*
    |--------------------------------------------------------------------------
    | 默认发送配置(目前只开通使用Aliyun)
    |--------------------------------------------------------------------------
    |
    | strategy 为策略器，默认使用「顺序策略器」，可选值有：
    |       - \Overtrue\EasySms\Strategies\OrderStrategy::class  顺序策略器
    |       - \Overtrue\EasySms\Strategies\RandomStrategy::class 随机策略器
    |
    | gateways 设置可用的发送网关，可用网关：
    |       - aliyun 阿里云信
    |       - alidayu 阿里大于
    |       - yunpian 云片
    |       - submail Submail
    |       - luosimao 螺丝帽
    |       - yuntongxun 容联云通讯
    |       - huyi 互亿无线
    |       - juhe 聚合数据
    |       - sendcloud SendCloud
    |       - baidu 百度云
    |
     */
    'default'  => [
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'qcloud',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | 发送网关配置
    |--------------------------------------------------------------------------
    |
    | 可用的发送网关，基于网关列表，这里配置可用的发送网关必要的数据信息。
    |
     */
    'gateways' => [
        'errorlog' => [
            'file' => env('SMS_ERROR_LOG_PATH', '/tmp/easy-sms.log'),
        ],
        'qcloud'   => [
            'sdk_app_id' => env('SMS_APP_ID'),
            'app_key'    => env('SMS_APP_KEY'),
            'sign_name'  => env('SMS_SIGN_NAME'),
        ],
        'aliyun'   => [
            'access_key_id'     => env('SMS_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ACCESS_KEY_SECRET'),
            'sign_name'         => env('SMS_SIGN_NAME'),
        ],
    ],
];
