<?php

//vod.php 之前hardcode了vod的私钥，开源后会泄露腾讯云vod安全问题
$secret_id  = env("VOD_SECRET_ID");
$secret_key = env("VOD_SECRET_KEY");

return [
    // 该id与key为vod通用配置
    'secret_id'      => $secret_id,
    'secret_key'     => $secret_key,

    'datizhuanqian'  => [
        'class_id'   => 659560,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'nashipin'       => [
        'class_id'   => 732752,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'yinxiangshipin' => [
        'class_id'   => 701986,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'jinlinle'       => [
        'class_id'   => 726165,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongwaimao'     => [
        'class_id'   => 723230,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'ablm'           => [
        'class_id'   => 683489,
        'secret_id'  => 'AKID5uHPGuYSKvFrEJv80f4C4NTPkt62EXop',
        'secret_key' => 'wc3FAyYOyHK6zVnvnY292SdXBZhZGi14',
    ],
    'damei'          => [
        'class_id'   => 621613,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'huitanapp'      => [
        'class_id'   => 623411,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongwanche'     => [
        'class_id'   => 623412,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'linxinapp'      => [
        'class_id'   => 623413,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongzhenai'     => [
        'class_id'   => 623414,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dianmoge'       => [
        'class_id'   => 621049,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdianyao'    => [
        'class_id'   => 621585,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'ainicheng'      => [
        'class_id'   => 621586,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdianfa'     => [
        'class_id'   => 621587,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'youjianqi'      => [
        'class_id'   => 621588,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongmeiwei'     => [
        'class_id'   => 621589,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdaima'      => [
        'class_id'   => 621590,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongqizhi'      => [
        'class_id'   => 621591,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'tongjiuxiu'     => [
        'class_id'   => 621592,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdianmei'    => [
        'class_id'   => 621593,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'haxibiao'       => [
        'class_id'   => 622409,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'qunyige'        => [
        'class_id'   => 621594,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdianyi'     => [
        'class_id'   => 621595,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'caohan'         => [
        'class_id'   => 621596,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'gba-port'       => [
        'class_id'   => 621597,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'gba-life'       => [
        'class_id'   => 621598,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongwaiyu'      => [
        'class_id'   => 621599,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongyundong'    => [
        'class_id'   => 621600,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongwuli'       => [
        'class_id'   => 621601,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'jucheshe'       => [
        'class_id'   => 621602,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongshouji'     => [
        'class_id'   => 621607,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdiancai'    => [
        'class_id'   => 621608,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongshengyin'   => [
        'class_id'   => 621609,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'diudie'         => [
        'class_id'   => 621610,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdezhuan'    => [
        'class_id'   => 621611,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongmiaomu'     => [
        'class_id'   => 619195,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongtianqi'     => [
        'class_id'   => 624204,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongyinyue'     => [
        'class_id'   => 624203,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongyunqi'      => [
        'class_id'   => 624205,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdili'       => [
        'class_id'   => 624206,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongshengwu'    => [
        'class_id'   => 624207,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'donghuaxue'     => [
        'class_id'   => 624208,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongyuwen'      => [
        'class_id'   => 624209,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongshuxue'     => [
        'class_id'   => 624210,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'donglishi'      => [
        'class_id'   => 624211,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongzhengzhi'   => [
        'class_id'   => 624212,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'donghuamu'      => [
        'class_id'   => 619406,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dongdianhai'    => [
        'class_id'   => 625148,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
    'dianyintujie'   => [
        'class_id'   => 738586,
        'secret_id'  => $secret_id,
        'secret_key' => $secret_key,
    ],
];
