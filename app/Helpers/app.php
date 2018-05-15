<?php

function getUser()
{
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
