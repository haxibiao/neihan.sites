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

//search
Route::get('/search/hot-queries', 'Api\SearchController@hotQueries');

//APP注册
Route::get('register', 'Api\UserController@register');
Route::post('register', 'Api\UserController@register');

//评论
Route::middleware('auth:api')->post('/comment', 'Api\CommentController@save');
Route::middleware('auth:api')->get('/comment/{id}/like', 'Api\CommentController@like');
Route::middleware('auth:api')->get('/comment/{id}/report', 'Api\CommentController@report');
Route::get('/comment/{id}/{type}', 'Api\CommentController@get');


//follow
Route::middleware('auth:api')->post('/follow/{id}/{type}','Api\FollowController@toggle');
Route::middleware('auth:api')->get('/follows', 'Api\FollowController@follows');
Route::middleware('auth:api')->get('/follow/recommends', 'Api\FollowController@recommends');
Route::middleware('auth:api')->get('/follow/{id}/{type}', 'Api\FollowController@touch');

//notifications
Route::middleware('auth:api')->get('notification/chats', 'Api\NotificationController@chats');
Route::middleware('auth:api')->get('notification/chat/{id}', 'Api\NotificationController@chat');
Route::middleware('auth:api')->post('notification/chat/{id}/send', 'Api\NotificationController@sendMessage');
Route::middleware('auth:api')->get('notifications/{type}', 'Api\NotificationController@notifications');
//新投稿请求列表
Route::middleware('auth:api')->get('/new-request-categories', 'Api\NotificationController@newReuqestCategories');


//favorite
Route::middleware('auth:api')->post('/favorite/{id}/{type}', 'Api\FavoriteController@toggle');
Route::middleware('auth:api')->get('/favorite/{id}/{type}', 'Api\FavoriteController@get');

//like
Route::middleware('auth:api')->post('/like/{id}/{type}', 'Api\LikeController@toggle');
Route::get('/like/{id}/{type}', 'Api\LikeController@get');

//user

//获取推荐的作者
Route::middleware('auth:api')->get('/user/recommend', 'Api\UserController@recommend');
//获取用户详细资料
Route::get('/user/{id}', 'Api\UserController@getInfo');
//user unreads
Route::middleware('auth:api')->get('/unreads','Api\UserController@unreads');
//get user id by user name
Route::get('/user/by-name/{name}', 'Api\UserController@getInfoByName');
//获取用户上传的图片，可以按标题搜索
Route::get('/user/{id}/images', 'Api\UserController@getImages');
//获取用户上传的视频，可以按标题搜索
Route::get('/user/{id}/videos', 'Api\UserController@getVideos');
//获取用户发布的文章，可以按标题搜索
Route::get('/user/{id}/articles', 'Api\UserController@getArticles');
//api update user
Route::middleware('auth:api')->post('/user/{id}/update','Api\UserController@update');
//api update user_avatar
Route::middleware('auth:api')->post('/user/{id}/avatar','Api\UserController@update_avatar');


//所有分类
Route::get('categories', 'Api\CategoryController@getIndex');
//GET分类下的文章
Route::middleware('auth:api')->get('/category/{id}','Api\CategoryController@show');

//視頻列表
Route::get('videos', 'Api\VideoController@getIndex');
Route::get('/video/{id}', 'Api\VideoController@getShow');

//文章列表
Route::get('/articles', 'Api\ArticleController@getIndex');
Route::get('/article/{id}', 'Api\ArticleController@getShow');

//投稿
Route::middleware('auth:api')->get('/articles/check-category-{id}', 'Api\ArticleController@checkCategory');
//投稿、撤销投稿
Route::middleware('auth:api')->get('/article/{aid}/submit-category-{cid}', 'Api\ArticleController@submitCategory');
//批准、拒绝投稿请求
Route::middleware('auth:api')->get('/article/{aid}/approve-category-{cid}', 'Api\ArticleController@approveCategory');

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

//图片
Route::post('/image/save', 'Api\ImageController@store');
