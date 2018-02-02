<?php

//follow
Route::middleware('auth:api')->post('/follow/{id}/{type}','Api\FollowController@toggle');
Route::middleware('auth:api')->get('/follows', 'Api\FollowController@follows');
Route::middleware('auth:api')->get('/follow/recommends', 'Api\FollowController@recommends');
Route::middleware('auth:api')->get('/follow/{id}/{type}', 'Api\FollowController@touch');