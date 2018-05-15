<?php

// 搜索
Route::get('/search','SearchController@search');
// 相关用户
Route::get('/search/users','SearchController@user');
// 相关专题
Route::get('/search/categories','SearchController@category');
// 相关文集
Route::get('/search/collections','SearchController@collection');