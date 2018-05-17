<?php

use App\Article;
use App\Category;
use App\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

function stick_article($data, $auto = false)
{
    $items = [];
    if (Storage::exists("stick_articles")) {
        $json  = Storage::get('stick_articles');
        $items = json_decode($json, true);
    }
    $data['timestamp'] = time();
    if ($auto) {
        $items[] = $data;
    } else {
        $items = array_merge([$data], $items);
    }
    $json = json_encode($items);
    Storage::put("stick_articles", $json);
}

function get_top_articles()
{
    $articles        = [];
    $stick_articles  = get_stick_articles('轮播图');
    $leftCount       = 8 - count($stick_articles);
    $topped_articles = Article::where('is_top', 1)->where('image_top', '<>', '')->orderBy('id', 'desc')->take($leftCount)->get();
    foreach ($topped_articles as $item) {
        $articles[] = $item;
    }
    ;
    $articles = array_merge($stick_articles, $articles);
    return $articles;
}

function get_stick_articles($position = '', $all = false)
{
    $articles = [];
    if (Storage::exists("stick_articles")) {
        $json  = Storage::get('stick_articles');
        $items = json_decode($json, true);
        foreach ($items as $item) {
            if (!$all) {
                //position
                if (!empty($position) && $position != $item['position']) {
                    continue;
                }

                //expire
                if (\Carbon\Carbon::createFromTimestamp($item['timestamp'])->addDays($item['expire']) < \Carbon\Carbon::now()) {
                    continue;
                }
            }

            $article = Article::find($item['article_id']);
            if ($article) {
                $article->expire     = $item['expire'];
                $article->position   = $item['position'];
                $article->stick_time = diffForHumansCN(\Carbon\Carbon::createFromTimestamp($item['timestamp']));
                $articles[]          = $article;
            }
        }
    }
    return $articles;
}

function get_seoer_meta()
{
    $data = User::where('is_seoer', 1)->pluck('seo_meta')->toArray();
    $data = array_filter($data);
    return join(" ", $data);
}

function get_seo_meta()
{
    $meta = '';
    if (Storage::exists("seo_config")) {
        $json   = Storage::get('seo_config');
        $config = json_decode($json);
        $meta   = $config->seo_meta;
    }
    return $meta;
}

function get_seo_push()
{
    $push = '';
    if (Storage::exists("seo_config")) {
        $json   = Storage::get('seo_config');
        $config = json_decode($json);
        $push   = $config->seo_push;
    }
    return $push;
}

function get_seo_tj()
{
    $tj = '';
    if (Storage::exists("seo_config")) {
        $json   = Storage::get('seo_config');
        $config = json_decode($json);
        $tj     = $config->seo_tj;
    }
    return $tj;
}

function get_seoer_footer()
{
    $data = User::where('is_seoer', 1)->pluck('seo_push')->toArray();
    $data = array_filter($data);
    $push = join(" ", $data);
    $data = User::where('is_seoer', 1)->pluck('seo_tj')->toArray();
    $data = array_filter($data);
    $tj   = join(" ", $data);

    return $push . $tj;
}

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
