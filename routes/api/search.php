<?php


//serach
Route::post('/v2/search/{type}','Api\SearchController@serach');
Route::get('/user/{id}/serach_history','Api\SearchController@get_user_histroy');
Route::get('/user/{id}/clear_serach_history','Api\SearchController@clear_user_history');