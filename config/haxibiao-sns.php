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
        | Package's Category Model
        |--------------------------------------------------------------------------
         */
        'comment' => App\Comment::class,
    ],
    'follow'=>[
        /**
         * user的关注列表为followers
         * user为主动方
         */
        'active'=>[
            App\User::class=>'follows',
        ],
        /**
         * user的粉丝列表为followers
         * user为被动方
         */
        'passive'=>[
            App\User::class=>'followers',
            App\Category::class=>'follows',
            App\Collection::class=>'follows',
        ]
    ]


];
