<?php

namespace App\Http\Controllers;

use App\Article;

class IndexController extends Controller
{
    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = (object) [];

        //首页轮播图(暂时糊弄5个电影，引导去电影频道)
        $data->movies = cmsTopMovies();

        //首页专题
        $data->categories = cmsTopCategories();

        //合集视频
        $data->videoPosts = cmsTopVideos();

        //首页文章
        $data->articles = cmsTopArticles();
        return view('index.index')->with('data', $data);
    }

    public function app()
    {
        app_track_event('网页', 'App下载');

        return view('app');
    }

    public function aboutUs()
    {
        return view('index.about_us');
    }

    public function trending()
    {
        if (request('type') == 'thirty') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereIn('type', ['diagrams', 'articles', 'article'])
                ->where('status', '>', 0)
                ->where('updated_at', '>', \Carbon\Carbon::now()->addDays(-30))
                ->paginate(10);
            if ($articles) {
                $articles = Article::orderBy('hits', 'desc')
                    ->whereIn('type', ['diagrams', 'articles', 'article'])
                    ->where('status', '>', 0)
                    ->paginate(10);
            }
        } else if (request('type') == 'seven') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereIn('type', ['diagrams', 'articles', 'article'])
                ->where('status', '>', 0)
                ->where('updated_at', '>', \Carbon\Carbon::now()->addDays(-7))
                ->paginate(10);
            if ($articles) {
                $articles = Article::orderBy('hits', 'desc')
                    ->whereIn('type', ['diagrams', 'articles', 'article'])
                    ->where('status', '>', 0)
                    ->paginate(10);
            }
        } else {
            $articles = Article::where('status', '>', 0)
                ->whereIn('type', ['diagrams', 'articles', 'article'])
                ->orderBy('hits', 'desc')
                ->paginate(10);
        }

        return view('index.trending')->withArticles($articles);
    }

    public function write()
    {
        return view('write');
    }
}
