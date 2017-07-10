<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;

class IndexController extends Controller
{
    public function index()
    {
        $categories = get_categories(1);
        $data       = [];
        foreach ($categories as $cate_id => $cate) {
            $articles = Article::orderBy('id', 'desc')
                ->where('category_id', $cate_id)
                ->where('status', '>=', 0)
                ->take(5)
                ->get();
            $data[$cate->name] = $articles;
        }

        $carousel_items = get_carousel_items();
        $users          = User::orderBy('id', 'desc')->take(5)->get();
        $hot_articles = Article::orderBy('hits', 'desc')->take(4)->get();
        return view('index.index')
            ->withCarouselItems($carousel_items)
            ->withUsers($users)
            ->withHotArticles($hot_articles)
            ->withData($data);
    }

    public function app()
    {
        return view('index.app');
    }

    public function aboutUs() {
        return view('index.about_us');
    }
}
