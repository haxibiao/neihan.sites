<?php

namespace App\Http\Controllers;

use App\Article;
use Carbon\Carbon;

class IndexController extends Controller
{
    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = (object) [];

        //FIXME: 可以用Stickable的函数获取
        //置顶 - 电影 置顶优先原则（可无置顶）
        $data->movies = cmsTopMovies();

        //FIXME: 可以用Stickable的函数获取
        //置顶 - 专题
        $data->categories = cmsTopCategories();

        //FIXME: 可以用Stickable的函数获取
        //置顶 合集视频
        $data->videoPosts = cmsTopVideos();

        //FIXME: 可以用Stickable的函数获取
        //首页文章 - 可置顶部分优质文章避免首页脏乱数据
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
}
