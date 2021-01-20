<?php

return [
    'alipay' => [
        'app_id'              => env('ALIPAY_PAY_APPID'),
        'notify_url'          => env('APP_URL') . '/api/pay/alipay-notify',
        'return_url'          => env('APP_URL') . '/api/pay/alipay-return',
        'private_key'         => @file_get_contents('/etc/alipay/private_key'), //需要提前支付宝部署自己证书到服务器
        'ali_public_key'      => '/etc/alipay/alipayCertPublicKey_RSA2.crt',
        'app_cert_public_key' => '/etc/alipay/appCertPublicKey_2021001172621778.crt', //应用公钥证书路径
        'alipay_root_cert'    => '/etc/alipay/alipayRootCert.crt',
        'log'                 => [
            'file'     => storage_path('logs/pay/alipay.log'),
            'level'    => 'info',
            'type'     => 'daily',
            'max_file' => 30,
        ],
        'http'                => [
            'timeout'         => 30,
            'connect_timeout' => 30,
        ],
        'mode'                => 'normal',
    ],
    'wechat' => [
        'appid'       => env('WECHAT_APPID'),
        'key'         => env('WECHAT_PAY_KEY'),
        'mch_id'      => env('WECHAT_PAY_MCH_ID'),
        'cert_client' => '/etc/wechat/apiclient_cert.pem',
        'cert_key'    => '/etc/wechat/apiclient_key.pem',
        'notify_url'  => env('APP_URL') . '/api/pay/wechat-notify',
        'log'         => [
            'file'     => storage_path('logs/pay/wechat.log'),
            'level'    => 'debug',
            'type'     => 'daily',
            'max_file' => 30,
        ],
        'http'        => [
            'timeout'         => 30,
            'connect_timeout' => 30,
        ],
        'mode'        => 'normal',
    ],
];
