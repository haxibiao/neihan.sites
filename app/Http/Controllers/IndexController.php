<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use Auth;

class IndexController extends Controller
{
    public function index()
    {
        $has_follow_articles = false;
        //get user related categories ..
        if (Auth::check()) {
            $user = Auth::user();
            //get top n user followed categories
            if ($user->followingCategories()->count() > 6) {
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
                // get user followed categories related articles ...
                $articles = Article::with('user')->with('category')
                    ->where('status', '>', 0)
                    ->where('source_url','=','0')
                    ->whereIn('category_id', $categorie_ids)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);

                if (!$articles->isEmpty()) {
                    $has_follow_articles = true;
                }
            }
        }

        if (!$has_follow_articles) {
            $categories = Category::orderBy('updated_at', 'desc')
                ->where('count', '>=', 0)
                ->where('status','>=',0)
                ->orderBy('updated_at', 'desc')
                ->take(7)
                ->get();

            $articles = Article::with('user')->with('category')
                ->where('status', '>', 0)
                ->where('source_url','=','0')
                ->whereIn('category_id', $categories->pluck('id'))
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        //load more articles ...
        if (request()->ajax() || request('debug')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }

        $data             = (object) [];
        $data->categories = $categories;
        $data->articles   = $articles;
        $data->carousel   = Article::where('is_top', 1)->where('image_top', '<>', '')->orderBy('id', 'desc')->take(8)->get();

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

    public function trending()
    {
        if (request('type') == 'thirty') {
            $articles = Article::orderBy('hits', 'desc')
                ->where('status', '>', 0)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-30))
                ->paginate(10);
        } else if (request('type') == 'seven') {
            $articles = Article::orderBy('hits', 'desc')
                ->where('status', '>', 0)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-7))
                ->paginate(10);
        } else {
            $articles = Article::orderBy('id', 'desc')
                ->where('status', '>', 0)
                ->paginate(10);
        }

        return view('index.trending')->withArticles($articles);
    }
}
