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
//流量统计
require_once 'traffic.php';

Route::get('/', 'IndexController@index');
//app
Route::get('/app', 'IndexController@app');
Route::get('/about-us', 'IndexController@aboutUs');
Route::get('/trending', 'IndexController@trending');

//动态 
Route::post('/post', 'ArticleController@storePost');

//问答
Route::resource('/question', 'QuestionController');
Route::resource('/answer', 'AnswerController');
Route::get('/categories-for-question', 'QuestionController@categories');
Route::get('/question-bonused', 'QuestionController@bonused');
Route::post('/question-add', 'QuestionController@add')->name('question.add');

//搜索
Route::get('/search', 'SearchController@search');
Route::get('/search/users', 'SearchController@searchUsers');
Route::get('/search/video', 'SearchController@searchVideos');
Route::get('/search/categories', 'SearchController@searchCategories');
Route::get('/search/collections', 'SearchController@searchCollections');

//文章
Route::get('/drafts', 'ArticleController@drafts');
//文章 slug
// Route::get('/article/{slug}', 'ArticleController@showBySlug')->where('slug','\D+');
Route::resource('/article', 'ArticleController');


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
Route::get('/video/list','VideoController@list');
Route::resource('/video', 'VideoController');

//后台
Route::get('/admin', 'AdminController@index');
//管理用户
Route::get('/admin/users', 'AdminController@users');
//seo config
Route::get('/admin/seo-config', 'AdminController@seoConfig');
Route::post('/admin/save-seo-config', 'AdminController@saveSeoConfig')->name('admin.save_seo_config');
//友情链接
Route::get('/admin/friend-links', 'AdminController@friendLinks');
Route::post('/admin/add-friend-links', 'AdminController@addFriendLink')->name('admin.add_friend_link');
Route::post('/admin/delete-friend-link', 'AdminController@deleteFriendLink')->name('admin.delete_friend_link');
//文章推送
Route::get('/admin/article-push', 'AdminController@article_push');
Route::post('/admin/push-article', 'AdminController@push_article')->name('admin.push_article');
//stick文章
Route::get('/admin/stick-articles', 'AdminController@articleSticks');
Route::post('/admin/stick-article', 'AdminController@articleStick')->name('admin.stick_article');
Route::post('/admin/delete-stick-article', 'AdminController@deleteStickArticle')->name('admin.delete_stick_article');
//stick视频
Route::get('/admin/stick-videos', 'AdminController@videoSticks');
Route::post('/admin/stick-videos', 'AdminController@videoStick')->name('admin.stick_video');
Route::post('/admin/delete-stick-videos', 'AdminController@deleteStickVideos')->name('admin.delete_stick_video');



//stickcategory
Route::get('/admin/stick-categorys', 'AdminController@categorySticks');
Route::post('/admin/stick-category', 'AdminController@categoryStick')->name('admin.stick_category');
Route::post('/admin/delete-stick-category', 'AdminController@deleteStickCategory')->name('admin.delete_stick_category');
Route::get('/admin/stick-video-categorys','AdminController@videoCategorySticks');
Route::post('/admin/stick-video-category', 'AdminController@videoCategoryStick')->name('admin.stick_video_category');
Route::post('/admin/delete-stick-video-category', 'AdminController@deleteStickVideoCategory')->name('admin.delete_stick_video_category');
//logs
Route::get('/logshow', 'LogController@logShow');
Route::get('/logclear', 'LogController@logClear');
Route::get('/debug', 'LogController@debug');

//weixin
Route::get('/wechat', 'WechatController@serve');

//支付
Route::get('/pay', 'PayController@pay');
//alipay
Route::get('alipay/wap/pay', 'Alipay\WapController@wapPay');
Route::get('alipay/wap/return', 'Alipay\WapController@wapReturn');
Route::get('alipay/wap/notify', 'Alipay\WapController@wapNotify');

//qrcode
Route::get('/share/weixin/','ShareController@shareWechat');


//search_log
Route::get('/searchQuery', 'SearchController@search_all');

//sitemap
Route::get('sitemap', 'SitemapController@index');

//last, use category name_en
Route::get('/{name_en}', 'CategoryController@name_en');
