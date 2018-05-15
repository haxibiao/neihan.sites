<?php

//相似问答
Route::middleware('auth:api')->get('/suggest-question', 'Api\QuestionController@suggest');
//问答
Route::middleware('auth:api')->get('/question/{id}', 'Api\QuestionController@question');
// 举报
Route::middleware('auth:api')->get('/report-question-{id}', 'Api\QuestionController@reportQuestion');
// 收藏问题，内逻辑同收藏文章...
Route::middleware('auth:api')->get('/favorite-question-{id}', 'Api\QuestionController@favoriteQuestion');
// 回答
Route::middleware('auth:api')->get('/answer/{id}', 'Api\QuestionController@answer');
// 回答下按钮操作
Route::middleware('auth:api')->get('/like-answer-{id}', 'Api\QuestionController@likeAnswer');
Route::middleware('auth:api')->get('/unlike-answer-{id}', 'Api\QuestionController@unlikeAnswer');
Route::middleware('auth:api')->get('/report-answer-{id}', 'Api\QuestionController@reportAnswer');
Route::middleware('auth:api')->get('/delete-answer-{id}', 'Api\QuestionController@deleteAnswer');
// 邀请列表
Route::get('/question-{id}-uninvited', 'Api\QuestionController@questionUninvited');
// 点邀请
Route::middleware('auth:api')->get('/question-{qid}-invite-user-{id}', 'Api\QuestionController@questionInvite');
Route::middleware('auth:api')->post('/question-{id}-answered', 'Api\QuestionController@answered');
//删除问题
Route::middleware('auth:api')->get('/delete-question-{id}', 'Api\QuestionController@delete');

//commend question
Route::get('/commend-question','Api\QuestionController@commend');