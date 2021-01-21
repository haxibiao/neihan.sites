<?php

use Illuminate\Support\Facades\Route;

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

//sitemap
//Route::get('/sitemap.xml', 'SitemapController@index');
//Route::get('/sitemap', 'SitemapController@index');
Route::get('/sitemap/{site}/{type}', 'SitemapController@all');

//隐私政策
Route::redirect('/pivacy-and-policy', '/article/3098');

Route::any('/alipay/wap/notify', 'Alipay\WapController@wapNotify');
Route::any('/alipay/wap/return', 'Alipay\WapController@wapReturn');

//alipay
Route::get('alipay/wap/pay', 'Alipay\WapController@wapPay');
Route::get('alipay/wap/return', 'Alipay\WapController@wapReturn');
Route::get('alipay/wap/notify', 'Alipay\WapController@wapNotify');

//last, use category name_en (限制分类英文url5个字母以上，避免 /gql, /gqlp 会被这个路由拦截)
// $router->pattern('name_en', '\w{5,100}'); //最新测试好像没被拦截了
// Route::get('/{name_en}', 'CategoryController@name_en')->where('name_en', '(?!nova).*')->orWhere('name_en', '(?!api).*');
