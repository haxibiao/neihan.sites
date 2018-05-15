<?php

//APP登录
Route::get('login', 'Api\UserController@login');

//APP注册
Route::get('register', 'Api\UserController@register'); //测试用
Route::post('register', 'Api\UserController@register');