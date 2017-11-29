<?php

//v.1.0 首页
Route::get('/v1', function () {
	return view('v1.index');
});

//v.1.0 专题页
Route::get('/v1/category', function () {
	return view('v1.category');
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

//v.1.0 个人主页
Route::get('/v1/home', function () {
	return view('v1.home');
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