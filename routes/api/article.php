<?php

//for push article
Route::get('/fake-users', 'Api\ArticleController@fakeUsers');
Route::get('/article/import', 'Api\ArticleController@import');
Route::post('/article/import', 'Api\ArticleController@import');

//文章
Route::get('/articles', 'Api\ArticleController@index');
Route::get('/article/{id}', 'Api\ArticleController@show');
Route::get('/article/{id}/likes', 'Api\ArticleController@likes');

Route::middleware('auth:api')->post('/article/create', 'Api\ArticleController@store');
Route::middleware('auth:api')->put('/article/{id}/update', 'Api\ArticleController@update');
Route::middleware('auth:api')->delete('/article/{id}', 'Api\ArticleController@delete');
Route::middleware('auth:api')->get('/article/{id}/restore', 'Api\ArticleController@restore');
Route::middleware('auth:api')->get('/article/{id}/destroy', 'Api\ArticleController@destroy');
Route::middleware('auth:api')->get('/article-trash', 'Api\ArticleController@trash');