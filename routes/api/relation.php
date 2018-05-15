<?php  

Route::post('/article/{id}/json', 'Api\ArticleController@saveRelation');
//获取文章所有相关片段数据
Route::get('/article/{id}/lists', 'Api\ArticleController@getAllRelations');
//删除文章相关片段数据
Route::get('/article/{id}/del-{key}', 'Api\ArticleController@deleteRelation');
//获取文章相关片段数据
Route::get('/article/{id}/{key}', 'Api\ArticleController@getRelation');

//保存视频相关片段数据
Route::post('/video/{id}/json', 'Api\VideoController@saveRelation');
//获取视频所有相关片段数据
Route::get('/video/{id}/lists', 'Api\VideoController@getAllRelations');
//删除视频相关片段数据
Route::get('/video/{id}/del-{key}', 'Api\VideoController@deleteRelation');
//获取视频相关片段数据
Route::get('/video/{id}/{key}', 'Api\VideoController@getRelation');