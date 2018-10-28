<?php

use App\Article;
use App\Category;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

//TODO:: hardcode 获取3级分类,今后需要支持无限分类
function get_categories($full = 0, $type = 'article', $for_parent = 0)
{
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

function get_carousel_items($category_id = 0)
{
    $carousel_items = [];
    $agent          = new Agent();
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
    $carousel_index   = 0;
    foreach ($top_pic_articles as $article) {
        $item = [
            'index'       => $carousel_index,
            'id'          => $article->id,
            'title'       => $article->title,
            'description' => $article->description,
            'image_url'   => $article->image_url,
            'image_top'   => $article->image_top,
        ];
        $carousel_items[] = $item;
        $carousel_index++;
    }
    return $carousel_items;
}

function base_uri()
{
    $http    = Request::secure() ? 'https://' : 'http://';
    $baseUri = $http . Request::server('HTTP_HOST');
    return $baseUri;
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

//记录行为到traffic表中
function recordTaffic($request, $path = null, $article_id = null, $user_id = null, $is_app = null)
{
        $traffic     = new \App\Traffic();
        $traffic->ip = get_ip();

        $agent                  = new \Jenssegers\Agent\Agent();
        $traffic->is_desktop    = $agent->isDesktop();
        $traffic->is_mobile     = $agent->isMobile();
        $traffic->is_phone      = $agent->isPhone();
        $traffic->is_tablet     = $agent->isTablet();
        $traffic->is_wechat     = $agent->match('micromessenger');
        $traffic->is_android_os = $agent->isAndroidOS();
        $traffic->is_robot      = $agent->isRobot();

        $traffic->device   = $agent->device();
        $traffic->platform = $agent->platform();
        $traffic->browser  = $agent->browser();
        $traffic->robot    = $agent->robot();
        if($path){
            $traffic->path = $path;
        }

        $traffic->referer = $request->server('HTTP_REFERER');
        if ($traffic->referer) {
            $traffic->referer_domain = parse_url($traffic->referer)['host'];
        }
        $traffic->date = Carbon::now()->format('Y-m-d');

        $traffic->year  = Carbon::now()->year;
        $traffic->month = Carbon::now()->month;
        $traffic->day   = Carbon::now()->day;

        $traffic->dayOfWeek   = Carbon::now()->dayOfWeek;
        $traffic->dayOfYear   = Carbon::now()->dayOfYear;
        $traffic->daysInMonth = Carbon::now()->daysInMonth;
        $traffic->weekOfMonth = Carbon::now()->weekOfMonth;
        $traffic->weekOfYear  = Carbon::now()->weekOfYear;

        if($article_id && is_numeric($article_id)){
            $article = \App\Article::with('category')->find($article_id);
            if($article){
                $traffic->article_id = $article_id;
                if($traffic->category){
                    $traffic->category = $article->category->name;
                }
            }
        }



        $traffic->user_id = $user_id ?: getUserId();
        $traffic->is_app = $is_app ?: 0;

        $traffic->save();
}
