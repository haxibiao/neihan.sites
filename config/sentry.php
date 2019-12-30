<?php

use Illuminate\Support\Arr;

$config = [
    'dianmoge'     => 'http://82e2cdcae0a842e189b7fb60f2d6f423@sentry.haxibiao.cn:9000/7',
    'dongdianyao'  => 'http://ab80ece502b343688669c137666fc356@sentry.haxibiao.cn:9000/8',
    'dongdiancai'  => 'http://20236a4198164f2493987dcf6cfca32f@sentry.haxibiao.cn:9000/9',
    'dongdianfa'   => 'http://de424e62f4f64009b23db476ae094c53@sentry.haxibiao.cn:9000/10',
    'youjianqi'    => 'http://b4955d021ace467b8a37d3fe6804d579@sentry.haxibiao.cn:9000/11',
    'dongmeiwei'   => 'http://0be5f218ddb64826887cb462f752d8bc@sentry.haxibiao.cn:9000/12',
    'dongdaima'    => 'http://e972deb118bf4b87b37bfb7974d46d6a@sentry.haxibiao.cn:9000/13',
    'dongqizhi'    => 'http://a8cf5e79cfcc4d6f96e91f94e3d63195@sentry.haxibiao.cn:9000/14',
    'tongjiuxiu'   => 'http://6d204907213f4e91b52c3261ed94cd75@sentry.haxibiao.cn:9000/15',
    'dongdianmei'  => 'http://40cb3cfceb3f45c8a17e7bc4216a3347@sentry.haxibiao.cn:9000/16',
    'haxibiao'     => 'http://cfd09fd623cd4b3e8cef465e8fb721ae@sentry.haxibiao.cn:9000/17',
    'qunyige'      => 'http://7cd0055b1d0748b08cdf005ad992eb71@sentry.haxibiao.cn:9000/18',
    'dongdianyi'   => 'http://04feef4cabe0487981acb51caf0877be@sentry.haxibiao.cn:9000/3',
    'caohan'       => 'http://7aeb65c604a9474089088f5ea90ef723@sentry.haxibiao.cn:9000/19',
    'wanqugangxun' => 'http://9a70693ac07345e5b9e5bb3d90a5f75f@sentry.haxibiao.cn:9000/20',
    'gba-life'     => 'http://c742a9b2568447beaf3fe03596c35b85@sentry.haxibiao.cn:9000/21',
    'gba-port'     => 'http://706c2dacddaa45038b7e62b0f6df22c0@sentry.haxibiao.cn:9000/23',
    'dongyundong'  => 'http://5cd2ea18bd9941ac88d2b48a72168f35@sentry.haxibiao.cn:9000/24',
    'dongwuli'     => 'http://9d4cd7f18300428b9b728d140b3be4b5@sentry.haxibiao.cn:9000/25',
    'jucheshe'     => 'http://bb1e0e01cd624284ac2d4fa2f8e18429@sentry.haxibiao.cn:9000/26',
    'dongshouji'   => 'http://993a651669f744419a63048acdfd0eb5@sentry.haxibiao.cn:9000/27',
    'ainicheng'    => 'http://decc0c44587647f48d555c0e54c121b8@sentry.haxibiao.cn:9000/28',
    'dongshengyin' => 'http://fe9beb072a1d4970b44311889b774a54@sentry.haxibiao.cn:9000/29',
    'diudie'       => 'http://6d68e6e1e70345188389aa0c244f622e@sentry.haxibiao.cn:9000/30',
    'dongdezhuan'  => 'http://a43d8109fdf7458db81aad0d02e0bb16@sentry.haxibiao.cn:9000/31',
    'dongmiaomu'   => 'http://6ecda6a96ef8471ba33fb58d750b29a5@sentry.haxibiao.cn:9000/32',
    'dongwaiyu'    => 'http://441f41dbe87442eeb6c0fbc30e6fd66c@sentry.haxibiao.cn:9000/33',
    'dongyinyue' => 'http://fda58baee50045f38a5c3deee18dacde@sentry.haxibiao.cn:9000/42',
    'dongtianqi' => 'http://8b369b7ed75a479b9ce8e1b39a4bdab1@sentry.haxibiao.cn:9000/43',
    'dongyunqi' => 'http://3a444a8494504241a9f552900d28d07a@sentry.haxibiao.cn:9000/45',
    'dongdili' => 'http://a032eb2de26e4518822c3a76df095415@sentry.haxibiao.cn:9000/46',
    'dongshengwu' => 'http://2e09d31de213458cb86cc637ee2adaa6@sentry.haxibiao.cn:9000/47',
    'donghuaxue' => 'http://2cf763baaf14402a89c539122a60f08f@sentry.haxibiao.cn:9000/48',
    'dongyuwen' => 'http://58634a236c9e4a54bb38784a2d6395bc@sentry.haxibiao.cn:9000/49',
    'dongshuxue' => 'http://da41f0a2ff76467fbf36f2959fb1c607@sentry.haxibiao.cn:9000/50',
    'donglishi' => 'http://de1acd5f1dd2449b901a649be373dc62@sentry.haxibiao.cn:9000/51',
    'dongzhengzhi' => 'http://cbb6f1d0e4d240d199b3c0657a21cfda@sentry.haxibiao.cn:9000/52',
];
return [

//    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    'dsn'         => Arr::get($config, env('APP_NAME'), null),
    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    'breadcrumbs' => [

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

    ],

];
