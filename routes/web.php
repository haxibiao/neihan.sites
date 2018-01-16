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
Auth::routes();

Route::pattern('id', '\d+');

//index
Route::get('/', 'IndexController@index');
Route::get('/app', 'IndexController@app');
Route::get('/about-us', 'IndexController@aboutUs');
Route::get('/index/weekly', 'IndexController@weekly');
Route::get('/index/monthly', 'IndexController@monthly');
Route::get('/index/new-list', 'IndexController@recommendations_notes');

//v1
require_once 'v1/web.php';

//v2
require_once 'v2/web.php';

//sitemap
require_once 'sitemap.php';

// 问答
Route::resource('/interlocution','QuestionController');

// 问答更多分类
Route::get('/interlocution/more', function () {
	return view('interlocution.more');
});

//搜索
Route::get('/search', 'SearchController@search');

//文章
Route::resource('/article', 'ArticleController');
Route::resource('/category', 'CategoryController');
Route::get('/cate', 'CategoryController@categories_hot');
Route::get('/tag/{name}', 'TagController@tagname');
Route::resource('/tag', 'TagController');
Route::get('/new', 'ArticleController@article_new');
//片段
Route::resource('/snippet', 'SnippetController');

//流量
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
Route::resource('/traffic', 'TrafficController');

//用户
Route::get('/user/seo', 'UserController@seo');
Route::get('/user/{id}/videos', 'UserController@videos');
Route::get('/user/{id}/articles', 'UserController@articles');
Route::get('/user/{id}/drafts', 'UserController@drafts');
Route::get('/user/{id}/favorites', 'UserController@favorites');
Route::get('/user/{id}/likes', 'UserController@likes');
Route::resource('/user', 'UserController');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/searchQuery', 'SearchController@search_all')->name('search_all');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/login-as/{id}', 'HomeController@loginAs');
Route::get('/hxb-login-as/{name}', 'HomeController@hxbLoginAs');
Route::get('/wallet', 'UserController@wallet');
Route::get('/setting', 'UserController@setting');

//多媒体
Route::resource('/image', 'ImageController');
Route::get('/index/poster','ImageController@poster');
Route::get('/index/image-all','ImageController@poster_all');
Route::resource('/video', 'VideoController');

//collection
Route::resource('/collection', 'CollectionController');

//follow
Route::get('/follow', 'FollowController@index');

//message
Route::get('/chat/with/{user_id}', 'ChatController@chat');
Route::get('/notification', 'NotificationController@index');

//后台
Route::get('/admin', 'AdminController@index');
Route::get('/admin/users', 'AdminController@users');

//logs
Route::get('/logshow', 'LogController@logShow');
Route::get('/logclear', 'LogController@logClear');
Route::get('/debug', 'LogController@debug');

//weixin
Route::get('/wechat', 'WechatController@serve');
//badwords system
Route::resource('/badword', 'BadwordController');

//支付
Route::get('/pay', 'PayController@pay');
//alipay
Route::get('alipay/wap/pay', 'Alipay\WapController@wapPay');
Route::get('alipay/wap/return', 'Alipay\WapController@wapReturn');
Route::get('alipay/wap/notify', 'Alipay\WapController@wapNotify');

//games
Route::resource('/compare', 'CompareController');
Route::resource('/match', 'MatchController');
Route::resource('/team', 'TeamController');
Route::get('/make-team-matches', 'MatchController@makeTeamGroupMatches');

//last, use category name_en
Route::get('/{name_en}', 'CategoryController@name_en');
