<?php

//APP登录
Route::get('login', 'Api\UserController@login');

//APP注册
Route::get('register', 'Api\UserController@register'); //测试用
Route::post('register', 'Api\UserController@register');

//广告的配置(方便激励视频每看一次更新)
Route::get('/ad-config', 'Api\AppController@adConfig');
//app功能开关(含广告配置)
Route::get('/app-config', 'Api\AppController@index');
//app版本管理
Route::any('/app-version', 'Api\AppController@version');
