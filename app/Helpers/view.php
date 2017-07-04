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

function get_categories($full = 0, $for_parent = 0)
{
    $categories = [];
    if ($for_parent) {
        $categories[0] = null;
    }
    $category_items = \App\Category::where('status', '>=', 0)->get();
    foreach ($category_items as $item) {
        if ($item->level == 0) {
            $categories[$item->id] = $full ? $item : $item->name;
            if ($item->has_child) {
                foreach ($category_items as $item_sub) {
                    if ($item_sub->parent_id == $item->id) {
                        $categories[$item_sub->id] = $full ? $item_sub : ' -- ' . $item_sub->name;
                        foreach ($category_items as $item_subsub) {
                            if ($item_subsub->parent_id == $item_sub->id) {
                                $categories[$item_subsub->id] = $full ? $item_subsub : ' ---- ' . $item_subsub->name;
                            }
                        }
                    }
                }
            }
        }
    }
    $categories = \Illuminate\Support\Collection::make($categories);
    return $categories;
}
