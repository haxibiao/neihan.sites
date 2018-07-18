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
    $phpunit    = simplexml_load_file('phpunit.xml');
    $is_testing = $phpunit->php->xpath('env[@name="APP_ENV"]')[0]['value'] != 'prod';
} catch (Exception $ex) {

}
//如果不是生产环境，就按require的方式，不然Unit Test会出现404
if ($is_testing) {
    require 'api/user.php';
    require 'api/follow.php';
    require 'api/notification.php';
    require 'api/question.php';
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
    require_once 'api/question.php';
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

//create qrcode
Route::get('/share/weixin/', 'Api\shareController@shareWechat');

//收藏
Route::middleware('auth:api')->post('/favorite/{id}/{type}', 'Api\FavoriteController@toggle');
Route::middleware('auth:api')->get('/favorite/{id}/{type}', 'Api\FavoriteController@get');

//like赞
Route::middleware('auth:api')->post('/like/{id}/{type}', 'Api\LikeController@toggle');
Route::middleware('auth:api')->get('/like/{id}/{type}', 'Api\LikeController@get');
Route::get('/like/{id}/{type}/guest', 'Api\LikeController@getForGuest');

//图片
Route::get('/image', 'Api\ImageController@index');
Route::middleware('auth:api')->post('/image/save', 'Api\ImageController@store');

//視頻列表
Route::get('videos', 'Api\VideoController@index');
Route::get('/video/{id}', 'Api\VideoController@show');
Route::middleware('auth:api')->post('/video/save', 'Api\VideoController@store');
