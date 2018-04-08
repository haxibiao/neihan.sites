<?php

//for push article
Route::get('/fake-users', 'Api\ArticleController@fakeUsers');
Route::get('/article/import', 'Api\ArticleController@import');
Route::post('/article/import', 'Api\ArticleController@import');

//保存文章相关片段数据
Route::post('/article/{id}/json', 'Api\ArticleController@saveRelation');
//获取文章所有相关片段数据
Route::get('/article/{id}/lists', 'Api\ArticleController@getAllRelations');
//删除文章相关片段数据
Route::get('/article/{id}/del-{key}', 'Api\ArticleController@deleteRelation');
//获取文章相关片段数据
Route::get('/article/{id}/info/{key}', 'Api\ArticleController@getRelation');
//ajax  article
Route::middleware('auth:api')->post('/article/create', 'Api\ArticleController@store');
Route::middleware('auth:api')->put('/article/{id}/update', 'Api\ArticleController@update');
Route::middleware('auth:api')->delete('/article/{id}', 'Api\ArticleController@delete');
Route::middleware('auth:api')->get('/article/{id}/restore', 'Api\ArticleController@restore');
Route::middleware('auth:api')->get('/article/{id}/destroy', 'Api\ArticleController@destroy');
Route::middleware('auth:api')->get('/article-trash', 'Api\ArticleController@trash');