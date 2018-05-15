<?php

//v.1.0 首页
Route::get('/v1', function () {
	return view('v1.index');
});

//v.1.0 专题页
Route::get('/v1/category/1', function () {
	return view('v1.category_home');
});

//v.1.0 专题页
Route::get('/v1/category/2', function () {
	return view('v1.category_user');
});

//v.1.0 文集页
Route::get('/v1/collection', function () {
	return view('v1.collection');
});

//v.1.0 新建专题页
Route::get('/v1/collections/new', function () {
	return view('v1.category_new_home');
});

//v.1.0 更多专题列表页
Route::get('/v1/categories', function () {
	return view('v1.categories');
});

//v.1.0 详细页
Route::get('/v1/detail', function () {
	return view('v1.detail');
});

//v.1.0 用户页
Route::get('/v1/user', function () {
	return view('v1.user');
});

//v.1.0 用户喜欢文章页
Route::get('/v1/user/liked_note', function () {
	return view('v1.liked_note_user');
});

//v.1.0 用户关注专题文集页
Route::get('/v1/user/subscription', function () {
	return view('v1.subscription_user');
});

//v.1.0 用户的用户关注页
Route::get('/v1/user/following', function () {
	return view('v1.following_user');
});

//v.1.0 用户粉丝页
Route::get('/v1/user/followers', function () {
	return view('v1.followers_user');
});

//v.1.0 个人主页
Route::get('/v1/home', function () {
	return view('v1.home');
});

//v.1.0 个人喜欢文章页
Route::get('/v1/home/liked_note', function () {
	return view('v1.liked_note_home');
});

//v.1.0 个人关注专题文集页
Route::get('/v1/home/subscription', function () {
	return view('v1.subscription_home');
});

//v.1.0 个人的用户关注页
Route::get('/v1/home/following', function () {
	return view('v1.following_home');
});

//v.1.0 个人粉丝页
Route::get('/v1/home/followers', function () {
	return view('v1.followers_home');
});

//v.1.0 我的钱包
Route::get('/v1/wallet', function () {
	return view('v1.wallet');
});

//v.1.0 关注页
Route::get('/v1/follow', function () {
	return view('v1.follow');
});

//v.1.0 消息页
Route::get('/v1/notification', function () {
	return view('v1.notification');
});

//v.1.0 登录页
Route::get('/v1/sign_in', function () {
	return view('v1.sign_in');
});

//v.1.0 注册页
Route::get('/v1/sign_up', function () {
	return view('v1.sign_up');
});

//v.1.0 设置页
Route::get('/v1/setting', function () {
	return view('v1.setting');
});

//v.1.0 收藏文章页
Route::get('/v1/bookmark', function () {
	return view('v1.bookmark');
});

//v.1.0 新上榜页
Route::get('/v1/recommendations/notes', function () {
	return view('v1.recommendations_notes');
});

//v.1.0 7日热门页
Route::get('/v1/trending/weekly', function () {
	return view('v1.trending_weekly');
});

//v.1.0 30日热门页
Route::get('/v1/trending/monthly', function () {
	return view('v1.trending_monthly');
});

//v.1.0 推荐作者
Route::get('/v1/recommendations/users', function () {
	return view('v1.recommendations_users');
});