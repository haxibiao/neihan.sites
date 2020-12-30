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

    public function verification(){
        $meta = get_seo_meta();
        $url = request()->url();

        if(str_contains($url,'sogou')){
            preg_match_all('/<meta.*name="sogou_site_verification".*content="(.*)".*>/', $meta, $matches);
            $sogou = data_get($matches,'1.0');
            if($sogou){
                return response($sogou)
                    ->header('Content-Type', 'text/html');
            }
            abort(404);
        }

        if(str_contains($url,'shenma')){
            preg_match_all('/<meta.*name="shenma-site-verification".*content="(.*)".*>/', $meta, $matches);
            $shenma = data_get($matches,'1.0');
            if($shenma){
                return response('shenma-site-verification:'.$shenma)
                    ->header('Content-Type', 'text/plain');
            }
            abort(404);
        }
        abort(404);
    }
}
