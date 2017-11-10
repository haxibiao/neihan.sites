<?php

//v.1.0 首页
Route::get('/v1', function () {
	return view('v1.index');
});

//v.1.0 专题页
Route::get('/v1/category', function () {
	return view('v1.category');
});

//v.1.0 详细页
Route::get('/v1/detail', function () {
	return view('v1.detail');
});

//v.1.0 用户页
Route::get('/v1/user', function () {
	return view('v1.user');
});

//v.1.0 更多专题列表页
Route::get('/v1/categories', function () {
	return view('v1.categories');
});