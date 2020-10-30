<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Eloquent Models
    |--------------------------------------------------------------------------
     */

    'models' => [

        /*
        |--------------------------------------------------------------------------
        | Package's Content Model
        |--------------------------------------------------------------------------
         */

        'category' => App\Category::class,
        'article'  => App\Article::class,
        'post'     => App\Post::class,
        'issue'    => App\Issue::class,
        'collection'     => App\Collection::class,

    ],

    'share_config' => [
        'share_msg' => '#%s/share/post/%d#, #%s# 我收藏了一个无水印视频与你分享，一起来欣赏吧、打开【%s】即可直接观看视频',
        'share_collection_msg' => '#%s/share/collection/%d#, #%s#,打开【%s】,直接观看视频合集,玩视频就能赚钱~,'
    ],

    // 动态是否开启马甲号分发
    'post_open_vest' => env('POST_OPEN_VEST', false),

    // 合集默认封面图片
    'collection_default_logo' => 'http://haxibiao-1251052432.cos.ap-guangzhou.myqcloud.com/images/collection.png',

     // 是否开始无水印视频分享
    'enabled_video_share' => env('ENABLED_VIDEO_SHARE',false),
    // 超过这个大小的视频不参与视频分享 100M=50*1024*1024
    'video_threshold_size' => env('VIDEO_THRESHOLD_SIZE',50*1024*1024),
];
