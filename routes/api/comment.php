<?php

//评论

//发表新评论，回复评论，回复评论中的评论
Route::middleware('auth:api')->post('/comment', 'Api\CommentController@save');

// 点赞
Route::middleware('auth:api')->get('/comment/{id}/like', 'Api\CommentController@like');
// 举报
Route::middleware('auth:api')->get('/comment/{id}/report', 'Api\CommentController@report');

// 未登录查看评论列表
Route::get('/comment/{id}/{type}', 'Api\CommentController@get');

// 已登录查看评论列表，能获取是否已点赞，已举报状态
Route::middleware('auth:api')->get('/comment/{id}/{type}/with-token', 'Api\CommentController@getWithToken');