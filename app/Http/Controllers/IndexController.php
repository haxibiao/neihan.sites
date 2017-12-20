<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $has_follow_articles = false;
        //TODO:以后优化取数组算法
        if (Auth::check()) {
            $user = Auth::user();
            //获取用户follow过的category
            $follows       = $user->follows()->where('followed_type', 'categories')->take(7)->get();
            $categories    = [];
            $categorie_ids = [];
            foreach ($follows as $follow) {
                $category        = $follow->followed;
                $categories[]    = $category;
                $categorie_ids[] = $category->id;
            }
            //依靠获取到的categories来获取article
            $articles = Article::with('user')->with('category')->whereIn('category_id', $categorie_ids)
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
            if (!$articles->isEmpty()) {
                $has_follow_articles = true;
            }
        }

        if (!$has_follow_articles) {
            $categories = Category::orderBy('updated_at')
                ->where('type', 'article')
                ->where('count', '>', 0)
                ->take(7)
                ->get();
            $articles = Article::with('user')->with('category')->orderBy('updated_at')->paginate(10);
        }

        //为VUEajax加载准备数据
        if ($request->ajax() || request('debug')) {

            foreach ($articles as $article) {
                $article->time_ago     = $article->timeAgo();
                $article->has_image    = !empty($article->image_url);
                $article->small_img    = get_small_image($article->image_url);
                $article->user->avatar = $article->user->avatar();
                $article->description  = $article->description ? $article->description : str_limit(strip_tags($article->body));
            }
            return $articles;
        }

        $data             = (object) [];
        $data->categories = $categories;
        $data->articles   = $articles;

        return view('index.index')
            ->withData($data);
    }

    public function app()
    {
        return view('index.app');
    }

    public function aboutUs()
    {
        return view('index.about_us');
    }

    public function weekly()
    {
        $articles = Article::orderBy('hits', 'desc')->with('user')->paginate(10);
        return view('index.trending_weekly')
            ->withArticles($articles)
        ;
    }

    public function monthly()
    {
        $articles = Article::orderBy('hits', 'desc')->with('user')->paginate(10);
        return view('index.trending_monthly')
            ->withArticles($articles)
        ;
    }

    public function recommendations_notes()
    {
        $articles = Article::orderBy('created_at', 'desc')->with('user')->paginate(10);
        return view('index.new_list')
            ->withArticles($articles)
        ;
    }
}
