<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();
Route::pattern('id', '\d+');
Auth::routes(['verify' => true]);

Route::get('/test', function () {
    dispatch(new DelayRewaredTask(1));
    return 1;
});

Route::get('/', 'IndexController@index');

//隐私政策
Route::redirect('/pivacy-and-policy', '/article/3098');

//app
Route::get('/app', 'IndexController@app');
Route::get('/about-us', 'IndexController@aboutUs');
Route::get('/trending', 'IndexController@trending');

//动态
Route::post('/post/new', 'ArticleController@storePost');

//电影
Route::get('/movie/riju', 'MovieController@riju');
Route::get('/movie/meiju', 'MovieController@meiju');
Route::get('/movie/hanju', 'MovieController@hanju');
Route::get('/movie/gangju', 'MovieController@gangju');

Route::get('/movie/search', 'MovieController@search');
Route::resource('/movie', 'MovieController');

//问答

// Route::resource('/question', 'IssueController');
// Route::resource('/answer', 'ResolutionController');
Route::get('/categories-for-question', 'IssueController@categories');
Route::get('/question-bonused', 'IssueController@bonused');

//搜索
Route::get('/search', 'SearchController@search');
Route::get('/search/users', 'SearchController@searchUsers');
Route::get('/search/video', 'SearchController@searchVideos');
Route::get('/search/categories', 'SearchController@searchCategories');
Route::get('/search/collections', 'SearchController@searchCollections');

Route::any('/alipay/wap/notify', 'Alipay\WapController@wapNotify');
Route::any('/alipay/wap/return', 'Alipay\WapController@wapReturn');

//文章
Route::get('/drafts', 'ArticleController@drafts');
//文章 slug
// Route::get('/article/{slug}', 'ArticleController@showBySlug')->where('slug','\D+');
Route::resource('/article', 'ArticleController');
//因为APP二维码分享用了 /post/{id}
Route::resource('/post', 'ArticleController');

//管理专题
Route::get('/category/list', 'CategoryController@list');
Route::resource('/category', 'CategoryController');

Route::resource('/collection', 'CollectionController');
Route::get('/tag/{name}', 'TagController@tagname');
Route::resource('/tag', 'TagController');

//创作
Route::middleware('auth')->get('/write', 'IndexController@write');

//片段
Route::resource('/snippet', 'SnippetController');

//消息
Route::get('/notification', 'NotificationController@index');
Route::get('/chat/with/{user_id}', 'ChatController@chat');
//关注
Route::get('/follow', 'FollowController@index');

//用户
Route::get('/settings', 'UserController@settings');
Route::get('/user/{id}/videos', 'UserController@videos');
Route::get('/user/{id}/articles', 'UserController@articles');
Route::get('/user/{id}/drafts', 'UserController@drafts');
Route::get('/user/{id}/favorites', 'UserController@favorites');
Route::get('/user/{id}/questions', 'UserController@questions');

Route::get('/user/{id}/likes', 'UserController@likes');
Route::get('/user/{id}/followed-categories', 'UserController@likes');
Route::get('/user/{id}/followed-collections', 'UserController@likes');
Route::get('/user/{id}/followings', 'UserController@follows');
Route::get('/user/{id}/followers', 'UserController@follows');
Route::middleware('auth')->get('/wallet', 'UserController@wallet');
Route::resource('/user', 'UserController');

//dashbord
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/login-as/{id}', 'HomeController@loginAs');
Route::get('/hxb-login-as/{name}', 'HomeController@hxbLoginAs');

//多媒体
Route::resource('/image', 'ImageController');
Route::get('/video/list', 'VideoController@list');
Route::get('/video/{id}', 'VideoController@show');
Route::get('/video/{id}/process', 'VideoController@processVideo');
Route::resource('/video', 'VideoController');

Route::any('/share/post/{id}', 'ArticleController@shareVideo');

//logs
Route::get('/logshow', 'LogController@logShow');
Route::get('/logclear', 'LogController@logClear');
Route::get('/bug', 'LogController@debug');

Route::get('/debug-sentry', function () {
    throw new \Exception('My first Sentry error!');
});

//weixin
Route::get('/wechat', 'WechatController@serve');

//支付
Route::get('/pay', 'PayController@pay');
//alipay
Route::get('alipay/wap/pay', 'Alipay\WapController@wapPay');
Route::get('alipay/wap/return', 'Alipay\WapController@wapReturn');
Route::get('alipay/wap/notify', 'Alipay\WapController@wapNotify');

//qrcode
Route::get('/share/qrcode', 'SharingController@qrcode');

//search_log
Route::get('/searchQuery', 'SearchController@search_all');

//sitemap
Route::get('sitemap', 'SitemapController@index');

//last, use category name_en (限制分类英文url5个字母以上，避免 /gql, /gqlp 会被这个路由拦截)
// $router->pattern('name_en', '\w{5,100}'); //最新测试好像没被拦截了
// Route::get('/{name_en}', 'CategoryController@name_en')->where('name_en', '(?!nova).*')->orWhere('name_en', '(?!api).*');
