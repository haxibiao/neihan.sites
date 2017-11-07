<?php

//v.1.0 首页
Route::get('/v1', function () {
	return view('v1.index');
});

//v.1.0 分类页
Route::get('/v1/category', function () {
	return view('v1.category');
});

//v.1.0 详细页
Route::get('/v1/detail', function () {
	return view('v1.detail');
});