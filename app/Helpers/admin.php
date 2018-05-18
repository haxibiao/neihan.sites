<?php

use App\Article;
use App\Category;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

function get_top_categoires($top_categoires){
    $categories=[];

    $stick_categories=get_stick_categories();
    foreach($top_categoires as $category){
        $categories[]=$category;
    }

    $categories=array_merge($stick_categories,$categories);
    $categories=array_unique($categories);

    return $categories;
}


function get_top_categories_count(){
    $categories=[];

    $stick_categories=get_stick_categories();
    $leftCount=7-count($stick_categories);
    return $leftCount;
}


function get_stick_categories($all = false)
{
    $categories = [];
    if (Storage::exists("stick_categories")) {
        $json  = Storage::get('stick_categories');
        $items = json_decode($json, true);
        foreach ($items as $item) {
            if (!$all) {
                //expire
                if (Carbon::createFromTimestamp($item['timestamp'])->addDays($item['expire']) < Carbon::now()) {
                    continue;
                }
            }

            $category = category::find($item['category_id']);
            if ($category) {
                $category->reason     = !empty($item['reason']) ? $item['reason'] : null;
                $category->expire     = $item['expire'];
                $category->stick_time = diffForHumansCN(Carbon::createFromTimestamp($item['timestamp']));
                $categories[]          = $category;
            }
        }
    }
    return $categories;
}


function stick_category($data,$auto=false)
{
    $items = [];

    if (Storage::exists("stick_categories")) {
        $json = Storage::get("stick_categories");

        foreach (json_decode($json, true) as $item) {
            //expire
            if (Carbon::createFromTimestamp($item['timestamp'])->addDays($item['expire']) < Carbon::now()) {
                continue;
            }
            $items[] = $item;
        }
    }

    $data['timestamp']=time();
    if($auto){
        $items[]=$data;
    }else{
        $items = array_merge([$data], $items);
    }
    $json=json_encode($items);
    Storage::put("stick_categories",$json);
}

function stick_article($data, $auto = false)
{
    $items = [];
    if (Storage::exists("stick_articles")) {
        $json = Storage::get('stick_articles');
        //auto clear expired items
        foreach (json_decode($json, true) as $item) {
            //expire
            if (Carbon::createFromTimestamp($item['timestamp'])->addDays($item['expire']) < Carbon::now()) {
                continue;
            }
            $items[] = $item;
        }
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
                if (Carbon::createFromTimestamp($item['timestamp'])->addDays($item['expire']) < Carbon::now()) {
                    continue;
                }
            }

            $article = Article::find($item['article_id']);
            if ($article) {
                $article->reason     = !empty($item['reason']) ? $item['reason'] : null;
                $article->expire     = $item['expire'];
                $article->position   = $item['position'];
                $article->stick_time = diffForHumansCN(Carbon::createFromTimestamp($item['timestamp']));
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
