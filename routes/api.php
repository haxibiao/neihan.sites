<?php

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
$is_testing = false;
try {
    $phpunit    = simplexml_load_string(file_get_contents('../phpunit.xml'));
    $is_testing = $phpunit->php->xpath('env[@name="APP_ENV"]')[0]['value'] != 'prod';
} catch (Exception $ex) {

}
//如果不是生产环境，就按require的方式，不然Unit Test会出现404
if ($is_testing) {
    require 'api/user.php';
    require 'api/follow.php';
    require 'api/notification.php';
    require 'api/issue.php';
    require 'api/article.php';
    require 'api/comment.php';
    require 'api/collection.php';
    //专题投稿
    require 'api/category.php';
    //保存文章相关片段数据
    require 'api/relation.php';
    require 'api/app.php';
    //搜索
    require 'api/search.php';
    require 'api/video.php';
} else {
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
    require_once 'api/video.php';
}

//返回URL二维码
Route::get('/share/qrcode/{url}', 'SharingController@qrcode');

//收藏
Route::middleware('auth:api')->post('/favorite/{id}/{type}', 'Api\FavoriteController@toggle');
Route::middleware('auth:api')->get('/favorite/{id}/{type}', 'Api\FavoriteController@get');

//获取VOD上传签名
Route::get('/signature/vod-{site}', 'Api\VodController@signature');

//like赞
Route::middleware('auth:api')->post('/like/{id}/{type}', 'Api\LikeController@toggle');
Route::middleware('auth:api')->get('/like/{id}/{type}', 'Api\LikeController@get');
Route::get('/like/{id}/{type}/guest', 'Api\LikeController@getForGuest');

//图片
Route::get('/image', 'Api\ImageController@index');
Route::middleware('auth:api')->post('/image', 'Api\ImageController@store');
Route::middleware('auth:api')->post('/image/save', 'Api\ImageController@store'); //兼容1.0 or vue上传视频接口

//app功能开关
Route::get('/app-config', 'Api\AppController@index');
//app版本管理
Route::any('/app-version', 'Api\AppController@version');

Route::post('/article/resolverDouyin', 'Api\ArticleController@resolverDouyinVideo');

Route::post('/media/import', 'Api\SpiderController@importDouyinSpider');

Route::namespace ('Api')->middleware('auth:api')->group(function () {
    Route::post('/background', 'UserController@saveBackground');
});
