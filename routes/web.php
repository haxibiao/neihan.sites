<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');

Route::get('/app', 'IndexController@app');
Route::get('/about-us', 'IndexController@aboutUs');

Route::resource('/article', 'ArticleController');
Route::resource('/user', 'UserController');
Route::get('/user/seo', 'UserController@seo');
Route::resource('/category', 'CategoryController');

Route::get('/traffic/log', 'TrafficController@log');
Route::get('/traffic/robot', 'TrafficController@robot');
Route::get('/traffic/device', 'TrafficController@device');
Route::get('/traffic/platform', 'TrafficController@platform');
Route::get('/traffic/browser', 'TrafficController@browser');
Route::get('/traffic/referer', 'TrafficController@referer');

Route::get('/traffic/referer_domain/{name}', 'TrafficController@referer_domain');
Route::get('/traffic/device/{name}', 'TrafficController@device');
Route::get('/traffic/browser/{name}', 'TrafficController@browser');
Route::get('/traffic/platform/{name}', 'TrafficController@platform');
Route::get('/traffic/robot/{name}', 'TrafficController@robot');
Route::get('/traffic/days-{days_ago}', 'TrafficController@index');
Route::resource('/traffic/', 'TrafficController');

Route::get('/tag/{name}', 'TagController@tagname');
Route::resource('/tag', 'TagController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/login-as/{id}', 'HomeController@loginAs');
Route::get('/hxb-login-as/{name}', 'HomeController@hxbLoginAs');
Route::resource('/image', 'ImageController');

Route::get('/admin', 'AdminController@index');
Route::get('/admin/users', 'AdminController@users');

//logs
Route::get('/logshow', 'LogController@logShow');
Route::get('/logclear', 'LogController@logClear');
Route::get('/debug','LogController@debug');

//weixin
Route::get('/wechat', 'WechatController@serve');

//last, use category name_en
Route::get('/{name_en}', 'CategoryController@name_en');