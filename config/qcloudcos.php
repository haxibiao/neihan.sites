<?php
/**
 * Created by PhpStorm.
 * User: lewis
 * Date: 2017/3/3
 * Time: 12:17
 */

// 设置COS所在的区域，对应关系如下：
//     华南  -> gz
//     华东  -> sh
//     华北  -> tj
$location = env('COS_LOCATION');
// 版本号
$version = 'v4.2.3';

return [
    'version'               => $version,
    'api_cos_api_end_point' => 'http://' . $location . '.file.myqcloud.com/files/v2/',
    'app_id'                => '1251052432',
    'secret_id'             => 'AKIDPbXCbj5C1bz72i7F9oDMHxOaXEgsNX0E',
    'secret_key'            => '70e2B4g27wWr1wf9ON8ev1rWzC9rKYXH',
    'user_agent'            => 'cos-php-sdk-' . $version,
    'time_out'              => 180,
    'location'              => $location,
];
