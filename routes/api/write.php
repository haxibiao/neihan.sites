<?php 

//写作编辑器
Route::middleware('auth:api')->get('/collections', 'Api\CollectionController@index');
Route::middleware('auth:api')->post('/collection/create', 'Api\CollectionController@create');
Route::middleware('auth:api')->post('/collection/{id}', 'Api\CollectionController@update');
Route::middleware('auth:api')->post('/collection/{id}/article/create', 'Api\CollectionController@createArticle');
Route::middleware('auth:api')->get('/article-{id}-move-collection-{cid}', 'Api\CollectionController@moveArticle');
Route::middleware('auth:api')->delete('/collection/{id}', 'Api\CollectionController@delete');