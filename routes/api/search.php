<?php


//serach
Route::post('/v2/search/{type}','Api\SearchController@search');
Route::get('/user/{id}/serach_history','Api\SearchController@get_user_histroy');
Route::get('/user/{id}/clear_serach_history/{history}','Api\SearchController@clear_user_history');