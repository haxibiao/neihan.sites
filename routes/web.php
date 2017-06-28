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
Route::get('/zhongyi', 'IndexController@zhongyi');
Route::get('/xiyi', 'IndexController@xiyi');

Route::resource('/article', 'ArticleController');
Route::resource('/user', 'UserController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/image', 'ImageController');