<?php

//搜索
Route::get('/search/hot-queries', 'Api\SearchController@hotQueries');
//个人搜索历史
Route::middleware('auth:api')->get('/search/latest-querylogs', 'Api\SearchController@latestQuerylog');
//清空最近5个搜索
Route::middleware('auth:api')->delete('/search/clear-querylogs', 'Api\SearchController@clearQuerylogs');
//清空单个搜索
Route::middleware('auth:api')->delete('/search/remove-querylog-{id}', 'Api\SearchController@removeQuerylog');
