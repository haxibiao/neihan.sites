<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function index()
    {
        $this->handle_app_load();

        $categories = get_categories(1);
        $data = [];
        foreach($categories as $cate_id => $cate) {
            $articles = Article::orderBy('id', 'desc')
                ->where('category_id', $cate_id)
                ->where('status', '>=', 0)
                ->take(6)->get();
            $data[$cate->name] = $articles;
        }

        $carousel_items = $this->get_carousel_items();
        return view('index.index')
            ->withCarouselItems($carousel_items)
            ->withData($data);
    }

    public function get_carousel_items($category_id = 0)
    {
        $carousel_items = [];
        $query          = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('has_pic', 1);
        if ($category_id) {
            $query = $query->where('category_id', $category_id);
        }
        $top_pic_articles = $query->take(5)->get();
        $carousel_index   = 0;
        foreach ($top_pic_articles as $article) {
            $item = [
                'index'     => $carousel_index,
                'title'     => $article->title,
                'image_url' => $article->image_url,
            ];
            $carousel_items[] = $item;
            $carousel_index++;
        }
        return $carousel_items;
    }

    public function handle_app_load()
    {
        if (Request::get('in_app')) {
            Cookie::queue('is_in_app', true, 60 * 24);
        } else {
            Cookie::queue(Cookie::forget('is_in_app'));
        }
    }
}
