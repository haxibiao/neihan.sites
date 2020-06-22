<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use haxibiao\media\Video;

/**
 * note: 如果您的项目还没有使用 haxibiao\media\Video，请import项目中Video的命名空间
 */
$factory->define(Video::class, function (Faker $faker) {

    static $user_id;

    return [
        // 发布用户
        'user_id' => $user_id,

        // 视频地址
        'path' => $faker->url
    ];
});
