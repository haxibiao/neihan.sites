<?php

//请求最新视频
Route::get('/getlatestVideo','Api\VideoController@getLatestVideo');

//視頻列表
Route::get('videos', 'Api\VideoController@index');
Route::get('/video/{id}', 'Api\VideoController@show');
Route::middleware('auth:api')->post('/video/save', 'Api\VideoController@store');