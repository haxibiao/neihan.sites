<?php
use Illuminate\Support\Facades\Auth;

function getUser()
{
    if (Auth::check()) {
        return Auth::user();
    }

    $user = session('user');

    if (!$user) {
        throw new \Exception('客户端还没登录...');
    }
    return $user;
}

function ajaxOrDebug()
{
    return request()->ajax() || request('debug');
}
