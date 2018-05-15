<?php

//分类
Route::get('categories', 'Api\CategoryController@index');
Route::get('recommend-categories', 'Api\CategoryController@page');
Route::get('category/{id}', 'Api\CategoryController@show');

Route::middleware('auth:api')->post('category/new-logo', 'Api\CategoryController@newLogo');
Route::middleware('auth:api')->post('category/{id}/edit-logo', 'Api\CategoryController@editLogo');
Route::middleware('auth:api')->post('category/{id}', 'Api\CategoryController@update');

//专题投稿
Route::middleware('auth:api')->get('/categories/check-category-{id}', 'Api\CategoryController@checkCategory');
//投稿、撤销投稿
Route::middleware('auth:api')->get('/categories/{aid}/submit-category-{cid}', 'Api\CategoryController@submitCategory');
//收录，移除
Route::middleware('auth:api')->get('/categories/{aid}/add-category-{cid}', 'Api\CategoryController@addCategory');
//批准、拒绝、移除投稿请求
Route::middleware('auth:api')->get('/categories/{aid}/approve-category-{cid}', 'Api\CategoryController@approveCategory');
//文章加入推荐专题
Route::middleware('auth:api')->get('/recommend-categories-check-article-{aid}', 'Api\CategoryController@recommendCategoriesCheckArticle');
//文章加入管理的专题
Route::middleware('auth:api')->get('/admin-categories-check-article-{aid}', 'Api\CategoryController@adminCategoriesCheckArticle');
//新投稿请求列表
Route::middleware('auth:api')->get('/new-request-categories', 'Api\NotificationController@newReuqestCategories');