<?php

use App\User;
use Illuminate\Http\Request;

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

require_once('api/user.php');

require_once('api/follow.php');

require_once('api/notification.php');

require_once('api/question.php');

require_once('api/article.php');

require_once('api/comment.php');

require_once('api/collection.php');

//专题投稿
require_once('api/category.php');

//保存文章相关片段数据
require_once('api/relation.php');

require_once('api/app.php');

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

//搜索
require_once('api/search.php');