<?php

declare (strict_types = 1);

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
    ],

    'share_config' =>[
        'share_msg' => '#%s/share/post/%d#, #%s# 我收藏了一个无水印视频与你分享，一起来欣赏吧、打开【%s】即可直接观看视频'
    ],

    // 动态是否开启马甲号分发
    'post_open_vest' => env('POST_OPEN_VEST',false)
];
