<?php

Route::get('/traffic/log', 'TrafficController@log');
Route::get('/traffic/robot', 'TrafficController@robot');
Route::get('/traffic/device', 'TrafficController@device');
Route::get('/traffic/platform', 'TrafficController@platform');
Route::get('/traffic/browser', 'TrafficController@browser');
Route::get('/traffic/referer', 'TrafficController@referer');
Route::get('/traffic/referer_domain/{name}', 'TrafficController@referer_domain');
Route::get('/traffic/device/{name}', 'TrafficController@device');
Route::get('/traffic/browser/{name}', 'TrafficController@browser');
Route::get('/traffic/platform/{name}', 'TrafficController@platform');
Route::get('/traffic/robot/{name}', 'TrafficController@robot');
Route::get('/traffic/days-{days_ago}', 'TrafficController@index');
Route::resource('/traffic', 'TrafficController');