<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

require_once 'api/user.php';
require_once 'api/follow.php';
require_once 'api/notification.php';
require_once 'api/issue.php';
require_once 'api/article.php';
require_once 'api/comment.php';
require_once 'api/collection.php';
//专题投稿
require_once 'api/category.php';
//保存文章相关片段数据
require_once 'api/relation.php';
require_once 'api/app.php';
//搜索
require_once 'api/search.php';
// require_once 'api/video.php';

Route::post('/image/upload', 'Api\ImageController@upload');

//返回URL二维码
Route::get('/share/qrcode/{url}', 'SharingController@qrcode');

//收藏
Route::middleware('auth:api')->post('/favorite/{id}/{type}', 'Api\FavoriteController@toggle');
Route::middleware('auth:api')->get('/favorite/{id}/{type}', 'Api\FavoriteController@get');

//获取VOD上传签名
Route::get('/signature/vod-{site}', 'Api\VodController@signature');

// Route::post('/live/screenShots', 'Api\LiveController@screenShots');
// Route::post('/live/cutOut', 'Api\LiveController@cutOutLive');
// Route::post('/live/recording', 'Api\LiveController@recording');

//like赞
Route::middleware('auth:api')->post('/like/{id}/{type}', 'Api\LikeController@toggle');
Route::middleware('auth:api')->get('/like/{id}/{type}', 'Api\LikeController@get');
Route::get('/like/{id}/{type}/guest', 'Api\LikeController@getForGuest');

//图片
Route::get('/image', 'Api\ImageController@index');
Route::middleware('auth:api')->post('/image', 'Api\ImageController@store');
Route::middleware('auth:api')->post('/image/save', 'Api\ImageController@store'); //兼容1.0 or vue上传视频接口

////注释的原因：RestFul方法废弃了，现在统一用GQL解析抖音视频
//Route::post('/article/resolverDouyin', 'Api\ArticleController@resolverDouyinVideo');
Route::post('/media/import', 'Api\SpiderController@importDouyinSpider');
Route::any('/media/oldHook', 'Api\SpiderController@hook');

Route::namespace ('Api')->middleware('auth:api')->group(function () {
    Route::post('/background', 'UserController@saveBackground');
});

Route::post('/douyin/import', 'Api\SpiderController@importDouYin');

Route::post('/withdraw', 'Api\WithdrawController@withdraws');

Route::any('/movie/history', 'Api\MovieController@movieHistory');
Route::post('/movie/toggle-like', 'Api\MovieController@toggoleLike');
Route::post('/movie/toggle-fan', 'Api\MovieController@toggoleFan');
