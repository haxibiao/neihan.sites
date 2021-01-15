<?php

declare (strict_types = 1);

return [
    /**
     * user的关注列表为followers
     * user为主动方
     */
    'active.follow'  => [
        App\User::class => 'followers',
    ],

    /**
     * user的粉丝列表为followers
     * user为被动方
     */
    'passive.follow' => [
        App\User::class       => 'followers',
        App\Category::class   => 'follows',
        App\Collection::class => 'follows',
    ],

];
