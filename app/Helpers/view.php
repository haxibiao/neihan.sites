<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

function is_in_app()
{
    return Cookie::get('is_in_app', false) || Request::get('in_app');
}

function get_active_css($path, $full_match = 0)
{
    $active = '';
    if (Request::path() == '/' && $path == '/') {
        $active = 'active';
    } else if (starts_with(Request::path(), $path)) {
        $active = 'active';
    }
    if ($full_match) {
        $active = Request::path() == $path ? 'active' : '';
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
    $pic_path = 'https://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=' . $qq . '&src_uin=www.feifeiboke.com&fid=blog&spec=100';

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
            $pic_path = 'https://qlogo2.store.qq.com/qzonelogo/' . $qq . '/1/1249809118';
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

function diffForHumansCN($time)
{
    if ($time) {
        $humanStr = $time->diffForHumans();
        $humanStr = str_replace('ago', '前', $humanStr);
        $humanStr = str_replace('seconds', '秒', $humanStr);
        $humanStr = str_replace('second', '秒', $humanStr);
        $humanStr = str_replace('minutes', '分钟', $humanStr);
        $humanStr = str_replace('minute', '分钟', $humanStr);
        $humanStr = str_replace('hours', '小时', $humanStr);
        $humanStr = str_replace('hour', '小时', $humanStr);
        $humanStr = str_replace('days', '天', $humanStr);
        $humanStr = str_replace('day', '天', $humanStr);
        $humanStr = str_replace('weeks', '周', $humanStr);
        $humanStr = str_replace('week', '周', $humanStr);
        $humanStr = str_replace('months', '月', $humanStr);
        $humanStr = str_replace('month', '月', $humanStr);
        $humanStr = str_replace('years', '年', $humanStr);
        $humanStr = str_replace('year', '年', $humanStr);
        return $humanStr;
    }
}
