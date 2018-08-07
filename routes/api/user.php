<?php

use App\User;
use Illuminate\Http\Request;

//用户设置
Route::middleware('auth:api')->get('/user', "Api\UserController@getSetting");

//用户
Route::get('/user/index', 'Api\UserController@index');
Route::middleware('auth:api')->get('/user/editors', 'Api\UserController@editors');
Route::get('/user/recommend', 'Api\UserController@recommend');
Route::middleware('auth:api')->post('/user/save-avatar', 'Api\UserController@saveAvatar');
Route::middleware('auth:api')->post('/user', 'Api\UserController@save');
Route::middleware('auth:api')->post('/user/{id}/follow', 'Api\UserController@follows');
Route::get('/user/{id}', 'Api\UserController@show'); 

//按用户名搜索用户
Route::get('/user/name/{name}', 'Api\UserController@name');
//获取用户上传的图片，可以按标题搜索
Route::get('/user/{id}/images', 'Api\UserController@images');
//获取用户上传的视频，可以按标题搜索
Route::get('/user/{id}/videos', 'Api\UserController@videos');
//获取用户发布的文章，可以按标题搜索
Route::get('/user/{id}/articles', 'Api\UserController@articles');

//获取用户上传的视频
Route::any('/user/{id}/videos/relatedVideos','Api\UserController@relatedVideos');
//获取相关分类视频
Route::any('/user/{id}/videos/sameVideos','Api\UserController@sameVideos');