<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use Auth;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Http\Request;
use RuntimeException;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $has_follow_articles = false;
        //TODO:以后优化取数组算法
        if (Auth::check()) {
            $user = Auth::user();
            //获取用户follow过的category
            $follows = $user->followingCategories()
                ->orderBy('id', 'desc')
                ->take(7)
                ->get();
            $categories    = [];
            $categorie_ids = [];
            foreach ($follows as $follow) {
                $category        = $follow->followed;
                $categories[]    = $category;
                $categorie_ids[] = $category->id;
            }
            //依靠获取到的categories来获取article
            $articles = Article::with('user')->with('category')
                ->whereIn('category_id', $categorie_ids)
                ->where('status', '>', 0)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            if (!$articles->isEmpty()) {
                $has_follow_articles = true;
            }

        }

        if (!$has_follow_articles) {
            $categories = Category::orderBy('updated_at', 'desc')
                ->where('type', 'article')
                ->where('count', '>', 0)
                ->orderBy('updated_at', 'desc')
                ->take(7)
                ->get();
            $articles = Article::with('user')->with('category')
                ->where('status', '>', 0)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        //为VUEajax加载准备数据
        if ($request->ajax() || request('debug')) {

            foreach ($articles as $article) {
                $article->time_ago      = $article->timeAgo();
                $article->has_image     = !empty($article->image_url);
                $article->primary_image = $article->primaryImage();
                $article->small_img     = get_small_image($article->image_url);
                $article->user->avatar  = $article->user->avatar();
                $article->description   = $article->description();
            }
            return $articles;
        }

        $data             = (object) [];
        $data->categories = $categories;
        $data->articles   = $articles;
        $data->carousel   = Article::where('is_top', 1)->orderBy('id', 'desc')->take(8)->get();
        foreach ($data->carousel as $article_img) {
            $article_img->image_top = $article_img->image_top();
        }
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
        $now      = now()->toDateTimeString();
        $week     = now()->subHours(24 * 7)->toDateTimeString();
        $articles = Article::with('user')
            ->whereBetween('created_at', [$week, $now])
            ->orderBy('hits', 'desc')
            ->paginate(10);
        return view('index.trending_weekly')
            ->withArticles($articles)
        ;
    }

    public function monthly()
    {
        $now      = now()->toDateTimeString();
        $week     = now()->subHours(24 * 30)->toDateTimeString();
        $articles = Article::with('user')
            ->whereBetween('created_at', [$week, $now])
            ->orderBy('hits', 'desc')
            ->paginate(10);
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

    public function demo()
    {
        Bugsnag::notifyException(new RuntimeException("Test error"));
    }
}
