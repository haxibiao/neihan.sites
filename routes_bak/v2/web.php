<?php

//v.2.0 首页
Route::get('/v2', function () {
	return view('v2.index');
});

//v.2.0 专题页
Route::get('/v2/category/1', function () {
	return view('v2.category_home');
});

//v.2.0 专题页
Route::get('/v2/category/2', function () {
	return view('v2.category_user');
});

//v.2.0 文集页
Route::get('/v2/collection', function () {
	return view('v2.collection');
});

//v.2.0 新建专题页
Route::get('/v2/collections/new', function () {
	return view('v2.category_new_home');
});

//v.2.0 更多专题列表页
Route::get('/v2/categories', function () {
	return view('v2.categories');
});

//v.2.0 详细页
Route::get('/v2/detail', function () {
	return view('v2.detail');
});

//v.2.0 用户页
Route::get('/v2/user', function () {
	return view('v2.user');
});

//v.2.0 用户喜欢文章页
Route::get('/v2/user/liked_note', function () {
	return view('v2.liked_note_user');
});

//v.2.0 用户关注专题文集页
Route::get('/v2/user/subscription', function () {
	return view('v2.subscription_user');
});

//v.2.0 用户的用户关注页
Route::get('/v2/user/following', function () {
	return view('v2.following_user');
});

//v.2.0 用户粉丝页
Route::get('/v2/user/followers', function () {
	return view('v2.followers_user');
});

//v.2.0 个人主页
Route::get('/v2/home', function () {
	return view('v2.home');
});

//v.2.0 个人喜欢文章页
Route::get('/v2/home/liked_note', function () {
	return view('v2.liked_note_home');
});

//v.2.0 个人关注专题文集页
Route::get('/v2/home/subscription', function () {
	return view('v2.subscription_home');
});

//v.2.0 个人的用户关注页
Route::get('/v2/home/following', function () {
	return view('v2.following_home');
});

//v.2.0 个人粉丝页
Route::get('/v2/home/followers', function () {
	return view('v2.followers_home');
});

//v.2.0 我的钱包
Route::get('/v2/wallet', function () {
	return view('v2.wallet');
});

//v.2.0 关注页
Route::get('/v2/follow', function () {
	return view('v2.follow');
});

//v.2.0 消息页
Route::get('/v2/notification', function () {
	return view('v2.notification');
});

//v.2.0 登录页
Route::get('/v2/sign_in', function () {
	return view('v2.sign_in');
});

//v.2.0 注册页
Route::get('/v2/sign_up', function () {
	return view('v2.sign_up');
});

//v.2.0 设置页
Route::get('/v2/setting', function () {
	return view('v2.setting');
});

//v.2.0 收藏文章页
Route::get('/v2/bookmark', function () {
	return view('v2.bookmark');
});

//v.2.0 新上榜页
Route::get('/v2/recommendations/notes', function () {
	return view('v2.recommendations_notes');
});

//v.2.0 7日热门页
Route::get('/v2/trending/weekly', function () {
	return view('v2.trending_weekly');
});

//v.2.0 30日热门页
Route::get('/v2/trending/monthly', function () {
	return view('v2.trending_monthly');
});

//v.2.0 推荐作者
Route::get('/v2/recommendations/users', function () {
	return view('v2.recommendations_users');
});

//v.2.0 测试组件
Route::get('/v2/demo', function () {
	return view('v2.demo');
});