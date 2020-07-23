<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {

    static $user_id;

    return [
        'answer'      => 'A',
        'submit'      => 1,
        'rank'        => 1,
        'category_id' => 1,
        'user_id'     => $user_id,
        'description' => '透过阳光，能将干燥的纸屑点燃的镜片是？',
        'selections'  => json_encode(['Selection' => [
            ['Text' => '选项1', 'Value' => 'A'],
            ['Text' => '选项2', 'Value' => 'B'],
            ['Text' => '选项3', 'Value' => 'C'],
        ]], JSON_UNESCAPED_UNICODE),
    ];
});
