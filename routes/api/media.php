<?php

use Illuminate\Support\Facades\Route;

//所有media相关的api...
Route::group(['namespace' => '\Haxibiao\Media\Http\Api'], function () {

    // Route::post('video/import-video', 'VideoController@importVideo');

    //请求最新视频
    Route::get('/getlatestVideo', 'VideoController@getLatestVideo');

    //視頻列表
    Route::get('videos', 'VideoController@index'); //旧的api
    Route::get('/video/{id}', 'VideoController@show');
    Route::get('/video/{id}/fix', 'VideoController@fix'); //修复封面

    Route::middleware('auth:api')->post('/video', 'VideoController@store'); //新短视频视频文件上传视频接口
    Route::middleware('auth:api')->post('/video/save', 'VideoController@store'); //兼容1.0上传vod视频后回调接口
    //获取视频截图
    Route::get('/{id}/covers', 'VideoController@covers');

    //COS转码后的回调地址
    Route::any('/cos/video/hook', 'VideoController@cosHookVideo');

    //获取VOD上传签名
    Route::get('/signature/vod-{site}', 'VodController@signature');
    Route::get('/signature/vod', 'VodController@mySignature');

    //图片
    Route::get('/image', 'ImageController@index');

    //上传图片
    Route::post('/image/upload', 'ImageController@upload'); // 兼容哈希表博客和日报？
    Route::middleware('auth:api')->post('/image', 'ImageController@store'); //主要上传图片api
    Route::middleware('auth:api')->post('/image/save', 'ImageController@store'); //兼容1.0 or vue上传视频接口

    //导入接口现在只保留gql的粘贴抖音
    // Route::post('/media/import', 'SpiderController@importDouyinSpider');

    //media服务抖音采集成功回调
    Route::any('/media/hook', 'SpiderController@hook');

    //COS视频回调
    Route::any('cos/video/hook', 'VideoController@cosHookVideo');
    //支持上传到vod
    Route::post('video', 'VideoController@store');
    //上传到自己服务器
    Route::post('video/upload', 'VideoController@store');
});
