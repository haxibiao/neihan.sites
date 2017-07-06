<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

function is_in_app()
{
    return Cookie::get('is_in_app', false) || Request::get('in_app');
}

function get_active_css($path)
{
    $active = '';
    if (Request::path() == '/' && $path == '/') {
        $active = 'active';
    } else if (starts_with(Request::path(), $path)) {
        $active = 'active';
    }
    return $active;
}

function get_avatar($user)
{
    if (!empty($user->avatar)) {
        return $user->avatar;
    }
    return get_qq_pic($user);
}

function get_qq_pic($user)
{
    $pic_path = '/img/qq_default_pic.gif';
    $qq       = $user->qq;
    if (empty($qq)) {
        $pattern = '/(\d+)\@qq\.com/';
        if (preg_match($pattern, strtolower($user->email), $matches)) {
            $qq = $matches[1];
        }
    }
    $pic_path = 'http://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=' . $qq . '&src_uin=www.feifeiboke.com&fid=blog&spec=100';

    return $pic_path;
}

function get_qzone_pic($user)
{
    $pic_path = '/img/qq_default_pic.gif';
    $qq       = $user->qq;
    if (empty($qq)) {
        $pattern = '/(\d+)\@qq\.com/';
        if (preg_match($pattern, strtolower($user->email), $matches)) {
            $qq       = $matches[1];
            $pic_path = 'http://qlogo2.store.qq.com/qzonelogo/' . $qq . '/1/1249809118';
        }
    }
    return $pic_path;
}

function get_image_index($max_id)
{
    if (empty(Cache::get('image_index'))) {
        $id_new = $max_id + 1;
        Cache::put('image_index', $id_new, 1);
    } else {
        $id_new = Cache::get('image_index') + 1;
        Cache::put('image_index', $id_new, 1);
    }
    return Cache::get('image_index');
}

function get_small_article_image($image_url)
{
    if (!ends_with($image_url, '.small.jpg')) {
        $image_url = $image_url . '.small.jpg';
    }
    return $image_url;
}
