<?php

use App\Badword;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

function domain_env(){
	if(env('APP_ENV')=='local'){
		return 'l.'.env('APP_DOMAIN');
	}

	if(env('APP_ENV')=='prod'){
		return env('APP_URL');
	}

	return '';
}

//TODO:: hardcode 获取3级分类,今后需要支持无限分类
function get_categories($full = 0, $type = 'article', $for_parent = 0) {
	$categories = [];
	if ($for_parent) {
		$categories[0] = null;
	}
	$category_items = Category::where('type', $type)->orderBy('order', 'desc')->get();
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

function get_carousel_items($category_id = 0) {
	$carousel_items = [];
	$agent = new Agent();
	if ($agent->isMobile()) {
		return $carousel_items;
	}
	$query = App\Article::orderBy('id', 'desc')
		->where('image_top', '<>', '')
		->where('is_top', 1);
	if ($category_id) {
		$query = $query->where('category_id', $category_id);
	}
	$top_pic_articles = $query->take(5)->get();
	$carousel_index = 0;
	foreach ($top_pic_articles as $article) {
		$item = [
			'index' => $carousel_index,
			'id' => $article->id,
			'title' => $article->title,
			'description' => $article->description,
			'image_url' => $article->image_url,
			'image_top' => $article->image_top,
		];
		$carousel_items[] = $item;
		$carousel_index++;
	}
	return $carousel_items;
}

function base_uri() {
	$http = Request::secure() ? 'https://' : 'http://';
	$baseUri = $http . Request::server('HTTP_HOST');
	return $baseUri;
}

function get_badwords() {
	$badwords = Cache::get('badwords');
	if (!$badwords) {
		$badwords = Badword::pluck('word')->toArray();
		Cache::put('badwords', $badwords, 60 * 24);
	}
	return $badwords;
}
function get_latest_tags() {
	$tags = Tag::orderBy('updated_at', 'desc')->take(12)->pluck('name')->toArray();
	$tags = join(',', $tags);
	return $tags;
}

//提取正文中的图片路径
function extractImagePaths($body)
{
    $imgs        = [];
    $pattern_img = '/src=\"(.*?)\"/';
    if (preg_match_all($pattern_img, $body, $matches)) {
        $img_urls = $matches[1];
        foreach ($img_urls as $img_url) {
            $imgs[] = parse_url($img_url)['path'];
        }
    }
    return $imgs;
}