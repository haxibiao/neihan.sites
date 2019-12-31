<?php

use Illuminate\Support\Arr;

$config = [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'             => true,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id'            => env('WECHAT_APPID', 'wx659d2f82c6ac4981'), // AppID
    'secret'            => env('WECHAT_SECRET', 'bb3852359ae82709ab0f30f05bca8560'), // AppSecret
    'token'             => env('WECHAT_TOKEN', 'dongdianyiapp'), // Token
    'aes_key'           => env('WECHAT_AES_KEY', '3dZF0qF9FfbYutRs8tIuBGawrN72VzVaFNbRfALUPl4'), // EncodingAESKey

    /**
     * 开放平台第三方平台配置信息
     */
    // 'open_platform' => [
    //     'app_id'  => env('WECHAT_OPEN_PLATFORM_APPID', ''),
    //     'secret'  => env('WECHAT_OPEN_PLATFORM_SECRET', ''),
    //     'token'   => env('WECHAT_OPEN_PLATFORM_TOKEN', ''),
    //     'aes_key' => env('WECHAT_OPEN_PLATFORM_AES_KEY', ''),
    // ],

    /**
     * 小程序配置信息
     */
    // 'mini_program' => [
    //     'app_id'  => env('WECHAT_MINI_PROGRAM_APPID', ''),
    //     'secret'  => env('WECHAT_MINI_PROGRAM_SECRET', ''),
    //     'token'   => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
    //     'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
    // ],

    /**
     * 路由配置
     */
    'route'             => [
        'enabled'                 => false, // 是否开启路由
        'attributes'              => [ // 路由 group 参数
            'prefix'     => null,
            'middleware' => null,
            'as'         => 'easywechat::',
        ],
        'open_platform_serve_url' => 'open-platform-serve', // 开放平台服务URL
    ],

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log'               => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * OAuth 配置
     *
     * only_wechat_browser: 只在微信浏览器跳转
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    // 'oauth' => [
    //     'only_wechat_browser' => false,
    //     'scopes'   => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
    //     'callback' => env('WECHAT_OAUTH_CALLBACK', '/examples/oauth_callback.php'),
    // ],

    /*
     * 微信支付
     */
    // 'payment' => [
    //     'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', 'your-mch-id'),
    //     'key'                => env('WECHAT_PAYMENT_KEY', 'key-for-signature'),
    //     'cert_path'          => env('WECHAT_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
    //     'key_path'           => env('WECHAT_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
    //     // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
    //     // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
    //     // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
    //     // ...
    // ],

    /*
     * 开发模式下的免授权模拟授权用户资料
     *
     * 当 enable_mock 为 true 则会启用模拟微信授权，用于开发时使用，开发完成请删除或者改为 false 即可
     */
    'enable_mock'       => env('WECHAT_ENABLE_MOCK', false),
    'mock_user'         => [
        'openid'     => 'odh7zsgI75iT8FRh0fGlSojc9PWM',
        // 以下字段为 scope 为 snsapi_userinfo 时需要
        'nickname'   => 'overtrue',
        'sex'        => '1',
        'province'   => '北京',
        'city'       => '北京',
        'country'    => '中国',
        'headimgurl' => 'http://wx.qlogo.cn/mmopen/C2rEUskXQiblFYMUl9O0G05Q6pKibg7V1WpHX6CIQaic824apriabJw4r6EWxziaSt5BATrlbx1GVzwW2qjUCqtYpDvIJLjKgP1ug/0',
    ],

    /**
     * 工厂APP 微信配置
     */
    'dianmoge'          => [
        'wechat_app' => [
            'appid'  => 'wxf79f886d16c1e588',
            'secret' => '659c1c4198e7d8f479734751ea9114a1',
        ],
    ],
    'qunyige'           => [
        'wechat_app' => [
            'appid'  => 'wx5f9e2198ddf38e26',
            'secret' => '1e8b517eab1f9f088b24c01ee382030b',
        ],
    ],
    'dongmeiwei'        => [
        'wechat_app' => [
            'appid'  => 'wx43118ce787bad78b',
            'secret' => '361c58504ab7609da69e1884de381bd9',
        ],
    ],
    'ainicheng'         => [
        'wechat_app' => [
            'appid'  => 'wx9a4d4a672f1d6dae',
            'secret' => '37085e747d45c1408686c34b740ce396',
        ],
    ],
    'youjianqi'         => [
        'wechat_app' => [
            'appid'  => 'wx81584853abab53bd',
            'secret' => 'a71b9dc2c1adb983c89aedc3e9878f3a',
        ],
    ],
    'dongdianyao'       => [
        'wechat_app' => [
            'appid'  => 'wxa5474243940d3811',
            'secret' => '8e41d661519e4013321532c987ebabd8',
        ],
    ],
    'dongdianmei'       => [
        'wechat_app' => [
            'appid'  => 'wxffd3abf9db7d3aa1',
            'secret' => '4c5194e522ee25105eed8c571134185可能是电话号码，是否拨号?a',
        ],
    ],
    'dongdiancai'       => [
        'wechat_app' => [
            'appid'  => 'wx1933dcd753fa3efe',
            'secret' => '58299b884c05298213ebdccd6b30b56a',
        ],
    ],
    'diudie'            => [
        'wechat_app' => [
            'appid'  => 'wxdcb59c0ab381bd70',
            'secret' => '9321cb107b3e76e1403167dd039b771b',
        ],
    ],
    'jucheshe'          => [
        'wechat_app' => [
            'appid'  => 'wx28b309f9423b331e',
            'secret' => 'f16e8b73547a5f9a67a6f7b7786a0163',
        ],
    ],
    'dongdianfa'        => [
        'wechat_app' => [
            'appid'  => 'wx66848b0e6c205c44',
            'secret' => '876308ff999015838f81533df362eac9',
        ],
    ],
    'dongshengyin'      => [
        'wechat_app' => [
            'appid'  => 'wx2ac38732d2913073',
            'secret' => 'd25be0a1e129f4591e8844e0c396861a',
        ],
    ],
    'dongwaiyu'         => [
        'wechat_app' => [
            'appid'  => 'wx71bfccf04b1bdfac',
            'secret' => 'b84b6887e0a63e5b284c187eb0fb2f05',
        ],
    ],
    'donghuamu'         => [
        'wechat_app' => [
            'appid'  => 'wxca5adfff8c348af2',
            'secret' => '6e79411155e8aa7a6ce3cc20a15e8262',
        ],
    ],
    'dongmiaomu'        => [
        'wechat_app' => [
            'appid'  => 'wxd44a736986f610cb',
            'secret' => 'cb9df7fbf5af14e5928b301a1bf6c740',
        ],
    ],
    'dongwuli'          => [
        'wechat_app' => [
            'appid'  => 'wxe2bef5c9bc2edeb0',
            'secret' => '09c5460758b5a1667e348c87ac2cb407',
        ],
    ],
    'dongqizhi'         => [
        'wechat_app' => [
            'appid'  => 'wx8ed3bfd7e7a06134',
            'secret' => '9f7cf2e012e29204d02ecdcaa420f577',
        ],
    ],
    'dongdianyi'        => [
        'wechat_app' => [
            'appid'  => 'wx4a953352ba5e5aee',
            'secret' => '11a1edd61129196ead561bf08f9a1040',
        ],
    ],
];

$config['wechat_app'] = Arr::get($config, config('app.name') . '.wechat_app');

return $config;
