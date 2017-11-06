<?php

use App\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

function is_weixin_editing() {
	return Cookie::get('is_weixin_editing', false) || Request::get('is_weixin');
}

function get_article_url($article) {
	$url = "/article/" . $article->id;
	if ($article->target_url) {
		$url = $article->target_url;
	}
	return $url;
}

function parse_video($body) {
	//TODO:: [视频的尺寸还是不完美，后面要获取到视频的尺寸才好处理, 先默认用半个页面来站住]
	$pattern_img_video = '/<img src=\"([^"]*?)\" data-video\=\"(\d+)\" ([^>]*?)>/iu';
	if (preg_match_all($pattern_img_video, $body, $matches)) {
		foreach ($matches[2] as $i => $match) {
			$img_html = $matches[0][$i];
			$video_id = $match;

			$video = Video::find($video_id);
			if ($video) {
				$video_html = '<div class="row"><div class="col-md-6"><div class="embed-responsive embed-responsive-4by3"><video class="embed-responsive-item" controls poster="' . get_img($video->cover) . '"><source src="' . $video->path . '" type="video/mp4"></video></div></div></div>';
				$body = str_replace($img_html, $video_html, $body);
			}
		}
	}
	return $body;
}

function get_items_col($items) {
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

function get_cached_index($max_id, $type = 'image') {
	if (empty(Cache::get($type . '_index'))) {
		$id_new = $max_id + 1;
		Cache::put($type . '_index', $id_new, 1);
	} else {
		$id_new = Cache::get($type . '_index') + 1;
		Cache::put($type . '_index', $id_new, 1);
	}
	return Cache::get($type . '_index');
}

function is_in_app() {
	return Cookie::get('is_in_app', false) || Request::get('in_app');
}

function get_top_nav_bg() {
	if (get_domain() == 'dianmoge.com') {
		return 'background-color: #000000';
	}
	if (get_domain() == 'dongmeiwei.com') {
		return 'background-color: #9d2932';
	}
	if (get_domain() == 'ainicheng.com') {
		return 'background-color: #F2B5C5';
	}
	if (get_domain() == 'qunyige.com') {
		return 'background-color: #f796c9';
	}

	return '';
}

function get_top_nav_color() {
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

function get_active_css($path, $full_match = 0) {
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

function get_full_url($path) {
	if (empty($path)) {
		return '';
	}
	if (starts_with($path, 'http')) {
		return $path;
	}
	return env('APP_URL') . $path;
}

function get_img($path) {
	if (starts_with($path, 'http')) {
		return $path;
	}
	if (\App::environment('local')) {
		if (!file_exists(public_path($path))) {
			return env('APP_URL') . $path;
		}
	}
	return $path;
}

function get_avatar($user) {
	if (!empty($user->avatar)) {
		return get_img($user->avatar);
	}
	return get_qq_pic($user);
}

function get_qq_pic($user) {
	$pic_path = '/img/qq_default_pic.gif';
	$qq = $user->qq;
	if (empty($qq)) {
		$pattern = '/(\d+)\@qq\.com/';
		if (preg_match($pattern, strtolower($user->email), $matches)) {
			$qq = $matches[1];
		}
	}
	$pic_path = 'https://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=' . $qq . '&src_uin=www.feifeiboke.com&fid=blog&spec=100';

	return $pic_path;
}

function get_qzone_pic($user) {
	$pic_path = '/img/qq_default_pic.gif';
	$qq = $user->qq;
	if (empty($qq)) {
		$pattern = '/(\d+)\@qq\.com/';
		if (preg_match($pattern, strtolower($user->email), $matches)) {
			$qq = $matches[1];
			$pic_path = 'https://qlogo2.store.qq.com/qzonelogo/' . $qq . '/1/1249809118';
		}
	}
	return $pic_path;
}

function get_small_image($image_url) {
	if (!str_contains($image_url, '.small.')) {
		$extension = pathinfo(parse_url($image_url)['path'], PATHINFO_EXTENSION);

		//fix article only!
		//如果是本地文章的图片,就尝试获取它的小图片. TODO::有点问题 先完成badword模块后再来修复
		if (str_contains($image_url, 'haxibiao')) {
			$image_url = str_replace('.' . $extension, '.small.' . $extension, $image_url);
		}
		if (!str_contains($image_url, 'storage/video')) {
			$image_url = str_replace('.' . $extension, '.small.' . $extension, $image_url);
		}

	}

	//fix dirty .png.small.jpg
	// $image_url = str_replace('.png.small.jpg', '.png.small.png', $image_url);

	return get_img($image_url);
}

function diffForHumansCN($time) {
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

function get_user_name($id) {
	if ($id) {
		$user = User::findOrFail($id);
		$name = $user->name;
		return $name;
	} else {
		return "system";
	}
}
