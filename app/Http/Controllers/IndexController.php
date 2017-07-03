<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cookie;
use App\Article;
use App\Category;

class IndexController extends Controller
{
    public function index()
    {
        $this->handle_app_load();

        $zhongyi_articles = Article::orderBy('id', 'desc')
            ->where('status', '>', 0)
            ->take(6)->get();
        $xiyi_articles = Article::orderBy('id', 'desc')
            ->where('status', '>', 0)
            ->take(6)->get();
        return view('index.index')
            ->withZhongyiArticles($zhongyi_articles)
            ->withXiyiArticles($xiyi_articles);
    }

    private function handle_app_load()
    {
        if (Request::get('in_app')) {
            Cookie::queue('is_in_app', true, 60 * 24);
        }
    }

    public function zhongyi()
    {
        $category = Category::where('name', '中医')->first();
        $articles = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('category_id', $category->id)
            ->paginate(10);
        return view('index.zhongyi')->withArticles($articles);
    }

    public function xiyi()
    {
        $category = Category::where('name', '西医')->first();
        $articles = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('category_id', $category->id)
            ->paginate(10);
        return view('index.xiyi')->withArticles($articles);
    }
}
