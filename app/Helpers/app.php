<?php
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnregisteredException;
function getUserId() {
    if(Auth::id()) {
        return Auth::id();
    }  
    if(request()->user()) {
        return request()->user()->id;
    }
    return 0;
}

function checkUser()
{
    return Auth::check() || session('user') || request()->user();
}

function getUser() 
{
    if (Auth::check()) {
        return Auth::user();
    }

    $user = session('user');

    if (!$user) {
        throw new UnregisteredException('客户端还没登录...');
    }
    return $user;
}

function ajaxOrDebug()
{
    return request()->ajax() || request('debug');
}
