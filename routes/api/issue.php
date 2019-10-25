<?php

//相似问答
Route::middleware('auth:api')->get('/suggest-question', 'Api\IssueController@suggest');
//问答
Route::middleware('auth:api')->get('/question/{id}', 'Api\IssueController@question');
// 举报
Route::middleware('auth:api')->get('/report-question-{id}', 'Api\IssueController@reportQuestion');
// 收藏问题，内逻辑同收藏文章...
Route::middleware('auth:api')->get('/favorite-question-{id}', 'Api\IssueController@favoriteQuestion');
// 回答
Route::middleware('auth:api')->get('/answer/{id}', 'Api\IssueController@answer');
// 回答下按钮操作
Route::middleware('auth:api')->get('/like-answer-{id}', 'Api\IssueController@likeAnswer');
Route::middleware('auth:api')->get('/unlike-answer-{id}', 'Api\IssueController@unlikeAnswer');
Route::middleware('auth:api')->get('/report-answer-{id}', 'Api\IssueController@reportAnswer');
Route::middleware('auth:api')->get('/delete-answer-{id}', 'Api\IssueController@deleteAnswer');
// 邀请列表
Route::get('/question-{id}-uninvited', 'Api\IssueController@questionUninvited');
// 点邀请
Route::middleware('auth:api')->get('/question-{qid}-invite-user-{id}', 'Api\IssueController@questionInvite');
Route::middleware('auth:api')->post('/question-{id}-answered', 'Api\IssueController@answered');
//删除问题
Route::middleware('auth:api')->get('/delete-question-{id}', 'Api\IssueController@delete');

//commend question
Route::get('/commend-question','Api\IssueController@commend');