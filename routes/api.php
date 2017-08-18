<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//APP登录
Route::get('login', 'Api\UserController@login');

//APP注册
Route::get('register', 'Api\UserController@register');
Route::post('register', 'Api\UserController@register');

//评论
Route::middleware('auth:api')->post('/comment', 'Api\CommentController@save');
Route::middleware('auth:api')->get('/comment/{id}/like', 'Api\CommentController@like');
Route::middleware('auth:api')->get('/comment/{id}/report', 'Api\CommentController@report');
Route::middleware('auth:api')->get('/comment/{id}/{type}', 'Api\CommentController@get');

//提交收藏
Route::middleware('auth:api')->post('/favorite/{id}/{type}', 'Api\FavoriteController@save');
//删除收藏
Route::middleware('auth:api')->delete('/favorite/{id}/{type}', 'Api\FavoriteController@delete');
//查询是否已收藏
Route::middleware('auth:api')->get('/favorite/{id}/{type}', 'Api\FavoriteController@get');

//提交赞
Route::middleware('auth:api')->post('/like/{id}/{type}', 'Api\LikeController@save');
//删除赞
Route::middleware('auth:api')->delete('/like/{id}/{type}', 'Api\LikeController@delete');
//查询是否已赞
Route::middleware('auth:api')->get('/like/{id}/{type}', 'Api\LikeController@get');

//获取用户详细资料
Route::get('/user/{id}', 'Api\UserController@getInfo');
//get user id by user name
Route::get('/user/by-name/{name}', 'Api\UserController@getInfoByName');
//获取用户上传的图片，可以按标题搜索
Route::get('/user/{id}/images', 'Api\UserController@getImages');
//获取用户上传的视频，可以按标题搜索
Route::get('/user/{id}/videos', 'Api\UserController@getVideos');


//所有分类
Route::get('categories', 'Api\CategoryController@getIndex');

//視頻列表
Route::get('videos', 'Api\VideoController@getIndex');
Route::get('/video/{id}', 'Api\VideoController@getShow');

//文章列表
Route::get('/articles', 'Api\ArticleController@getIndex');
Route::get('/article/{id}', 'Api\ArticleController@getShow');

//保存文章相关片段数据
Route::post('/article/{id}/json', 'Api\ArticleController@saveRelation');
//获取文章所有相关片段数据
Route::get('/article/{id}/lists', 'Api\ArticleController@getAllRelations');
//删除文章相关片段数据
Route::get('/article/{id}/del-{key}', 'Api\ArticleController@deleteRelation');
//获取文章相关片段数据
Route::get('/article/{id}/{key}', 'Api\ArticleController@getRelation');

//保存视频相关片段数据
Route::post('/video/{id}/json', 'Api\VideoController@saveRelation');
//获取视频所有相关片段数据
Route::get('/video/{id}/lists', 'Api\VideoController@getAllRelations');
//删除视频相关片段数据
Route::get('/video/{id}/del-{key}', 'Api\VideoController@deleteRelation');
//获取视频相关片段数据
Route::get('/video/{id}/{key}', 'Api\VideoController@getRelation');
