<?php

use App\Image;
use App\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

function get_submit_status($submited_status, $isAdmin = false)
{
    $submit_status = $isAdmin ? '收录' : '投稿';
    switch ($submited_status) {
        case '待审核':
            $submit_status = '撤回';
            break;
        case '已收录':
            $submit_status = '移除';
            break;
        case '已拒绝':
            $submit_status = '再次投稿';
            break;
        case '已撤回':
            $submit_status = '再次投稿';
            break;
        case '已移除':
            $submit_status = '再次收录';
            break;
    }

    return $submit_status;
}

function count_words($body)
{
    $body_text = strip_tags($body);
    preg_match_all('/[\x{4e00}-\x{9fff}]+/u', strip_tags($body), $matches);
    $str        = implode('', $matches[0]);
    $body_count = strlen(strip_tags($body)) - strlen($str) / 3 * 2;
    return $body_count;
}

function user_id()
{
    return Auth::check() ? Auth::user()->id : false;
}

function is_follow($type, $id)
{
    return Auth::check() ? Auth::user()->isFollow($type, $id) : false;
}

function checkEditor()
{
    return Auth::check() && Auth::user()->checkEditor();
}

function get_polymorph_types($type)
{
    return ends_with($type, 's') ? $type : $type . 's';
}

function is_weixin_editing()
{
    return Cookie::get('is_weixin_editing', false) || Request::get('is_weixin');
}

function get_article_url($article)
{
    $url = "/article/" . $article->id;
    if ($article->target_url) {
        $url = $article->target_url;
    }
    return $url;
}

function parse_image($body)
{
    //检测本地没图的时候取线上的
    if (\App::environment('local')) {
        $pattern_img = '/<img(.*?)src=\"(.*?)\"(.*?)>/';
        preg_match_all($pattern_img, $body, $matches);
        $imgs = $matches[2];
        foreach ($imgs as $img) {
            $image = Image::where('path', $img)->first();
            if ($image) {
                $body = str_replace($img, $image->url_prod(), $body);
            }
        }
    }
    return $body;
}

function parse_video($body)
{
    //TODO:: [视频的尺寸还是不完美，后面要获取到视频的尺寸才好处理, 先默认用半个页面来站住]
    $pattern_img_video = '/<img src=\"\/storage\/video\/thumbnail_(\d+)\.jpg\"([^>]*?)>/iu';
    if (preg_match_all($pattern_img_video, $body, $matches)) {
        foreach ($matches[1] as $i => $match) {
            $img_html = $matches[0][$i];
            $video_id = $match;

            $video = Video::find($video_id);
            if ($video) {
                $video_html = '<div class="row"><div class="col-md-6"><div class="embed-responsive embed-responsive-4by3"><video class="embed-responsive-item" controls poster="' . $video->cover . '"><source src="' . $video->path . '" type="video/mp4"></video></div></div></div>';
                $body       = str_replace($img_html, $video_html, $body);
            }
        }
    }
    return $body;
}

function get_items_col($items)
{
    if (is_array($items)) {
        if (count($items) >= 4) {
            return 'col-sm-4 col-md-3';
        }
        if (count($items) == 3) {
            return 'col-sm-4';
        }
    }
    return '';
}

function get_cached_index($max_id, $type = 'image')
{
    if (empty(Cache::get($type . '_index'))) {
        $id_new = $max_id + 1;
        Cache::put($type . '_index', $id_new, 1);
    } else {
        $id_new = Cache::get($type . '_index') + 1;
        Cache::put($type . '_index', $id_new, 1);
    }
    return Cache::get($type . '_index');
}

function is_in_app()
{
    return Cookie::get('is_in_app', false) || Request::get('in_app');
}

function get_top_nav_bg()
{
    if (get_domain() == 'dianmoge.com') {
        return 'background-color: #000000';
    }
    if (get_domain() == 'dongmeiwei.com') {
        return 'background-color: #9d2932';
    }
    if (get_domain() == 'ainicheng.com') {
        return 'background-color: #3b5795';
    }
    if (get_domain() == 'qunyige.com') {
        return 'background-color: #f796c9';
    }

    return '';
}

function get_top_nav_color()
{
    if (get_domain() == 'ainicheng.com') {
        return 'color: white';
    }
    if (get_domain() == 'dianmoge.com') {
        return 'color: white';
    }
    if (get_domain() == 'dongmeiwei.com') {
        return 'color: white';
    }
    return '';
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
        if (Request::path() == $path) {
            $active = 'active';
        }
    }
    return $active;
}

function get_full_url($path)
{
    if (empty($path)) {
        return '';
    }
    if (starts_with($path, 'http')) {
        return $path;
    }
    return env('APP_URL') . $path;
}

function get_qq_pic($qq)
{
    return 'https://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=' . $qq . '&src_uin=qq.com&fid=blog&spec=100';
}

function get_qzone_pic($qq)
{
    return 'https://qlogo2.store.qq.com/qzonelogo/' . $qq . '/1/' . time();
}

function diffForHumansCN($time)
{
    if ($time) {
        $humanStr = $time->diffForHumans();
        $humanStr = str_replace('from now', '以后', $humanStr);
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
