<?php

Route::middleware('auth:api')->get('/articles/check-category-{id}', 'Api\ArticleController@checkCategory');
//投稿、撤销投稿
Route::middleware('auth:api')->get('/article/{aid}/submit-category-{cid}', 'Api\ArticleController@submitCategory');
//批准、拒绝投稿请求
Route::middleware('auth:api')->get('/article/{aid}/approve-category-{cid}', 'Api\ArticleController@approveCategory');
//收录，移除
Route::middleware('auth:api')->get('/article/{aid}/add-category-{cid}', 'Api\ArticleController@addCategory');

//文章加入推荐专题 
Route::middleware('auth:api')->get('/recommend-categories-check-article-{aid}', 'Api\ArticleController@recommendCategoriesCheckArticle');

//文章加入管理的专题 
Route::middleware('auth:api')->get('/admin-categories-check-article-{aid}', 'Api\ArticleController@adminCategoriesCheckArticle');