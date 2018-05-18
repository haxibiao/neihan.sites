<?php

//answer info
Route::get('/answer/info/{id}','Api\AnswerController@get');
Route::middleware('auth:api')->get('/like-answer/{id}', 'Api\QuestionController@likeAnswer');
Route::middleware('auth:api')->get('/unlike-answer/{id}', 'Api\QuestionController@unlikeAnswer');
Route::middleware('auth:api')->get('/report-answer/{id}', 'Api\QuestionController@reportAnswer');
Route::middleware('auth:api')->get('/delete-answer/{id}', 'Api\QuestionController@deleteAnswer');
//邀请
Route::middleware('auth:api')->get('/user/{id}/question-invite/{qid}', 'Api\UserController@questionInvite');