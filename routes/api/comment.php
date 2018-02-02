<?php

//评论
Route::middleware('auth:api')->post('/comment', 'Api\CommentController@save');
Route::middleware('auth:api')->get('/comment/{id}/like', 'Api\CommentController@like');
Route::middleware('auth:api')->get('/comment/{id}/report', 'Api\CommentController@report');
Route::get('/comment/{id}/{type}', 'Api\CommentController@get');