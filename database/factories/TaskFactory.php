<?php

use Faker\Generator as Faker;

$factory->define(Haxibiao\Task\Task::class, function (Faker $faker) {
    return [
        'name'    => '测试试玩任务',
        'details' => '测试任务详情',
        'type'    => 0, //类型：0:新人任务 1:每日任务 2:成长任务(自定义任务)

    ];
});
