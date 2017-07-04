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

        $zhongyi_articles = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->take(6)->get();
        $xiyi_articles = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->take(6)->get();

        $carousel_items = $this->get_carousel_items();

        return view('index.index')
            ->withCarouselItems($carousel_items)
            ->withZhongyiArticles($zhongyi_articles)
            ->withXiyiArticles($xiyi_articles);
    }

    public function get_carousel_items($category_id = 0)
    {
        $carousel_items = [];
        $query          = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('is_has_pic', 1);
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

    public function zhongyi()
    {
        $category       = Category::where('name', '中医')->first();
        $carousel_items = $this->get_carousel_items($category->id);
        $articles       = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('category_id', $category->id)
            ->paginate(10);
        return view('index.zhongyi')
            ->withCarouselItems($carousel_items)
            ->withArticles($articles);
    }

    public function xiyi()
    {
        $category       = Category::where('name', '西医')->first();
        $carousel_items = $this->get_carousel_items($category->id);
        $articles       = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0)
            ->where('category_id', $category->id)
            ->paginate(10);
        return view('index.xiyi')
            ->withCarouselItems($carousel_items)
            ->withArticles($articles);
    }
}
