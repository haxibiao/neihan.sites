<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use Faker\Generator as Faker;
use haxibiao\content\Post;

$factory->define(Post::class, function (Faker $faker) {

    static $user_id;
    static $video_id;

    return [
        // 作者
        'user_id' => $user_id,

        // 视频ID
        'video_id' => $video_id,

        // 描述
        'description' => $faker->text('30'),

        // 内容
        'content' => $faker->text,

        // 状态
        'status' => Post::PUBLISH_STATUS,
    ];
});
