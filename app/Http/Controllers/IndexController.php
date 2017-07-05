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

        get_carousel_items();
        return view('index.index')
            ->withCarouselItems($carousel_items)
            ->withData($data);
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
