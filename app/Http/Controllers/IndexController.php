<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;

class IndexController extends Controller
{
    public function index()
    {
        $categories = get_categories(1);
        $data = [];
        foreach($categories as $cate_id => $cate) {
            $articles = Article::orderBy('id', 'desc')
                ->where('category_id', $cate_id)
                ->where('status', '>=', 0)
                ->take(6)->get();
            $data[$cate->name] = $articles;
        }

        $carousel_items = get_carousel_items();
        return view('index.index')
            ->withCarouselItems($carousel_items)
            ->withData($data);
    }
}
