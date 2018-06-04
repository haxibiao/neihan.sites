<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use App\Video;
use Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IndexController extends Controller
{
    public function index()
    {
        $has_follow_articles = false;
        //get user related categories ..
        $categorie_ids = [];

        $stick_categories = get_stick_categories();
        $top_count        = 7 - count($stick_categories);
        if ($top_count) {
            foreach ($stick_categories as $stick_category) {
                $categorie_ids[] = $stick_category->id;
            }
        }

        if (Auth::check()) {
            $user = Auth::user();
            //get top n user followed categories
            if ($user->followingCategories()->count() > 6) {
                $follows = $user->followingCategories()
                    ->orderBy('id', 'desc')
                    ->take($top_count)
                    ->get();
                $categories = [];

                foreach ($follows as $follow) {
                    $category        = $follow->followed;
                    $categories[]    = $category;
                    $categorie_ids[] = $category->id;
                }
                // get user followed categories related articles ...
                $articles = Article::with('user')->with('category')
                    ->where('status', '>', 0)
                    ->where('source_url', '=', '0')
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
                ->where('status', '>=', 0)
                ->orderBy('updated_at', 'desc')
                ->take($top_count)
                ->get();
            $articles = Article::with('user')->with('category')
                ->where('status', '>', 0)
                ->where('source_url', '=', '0')
                ->whereIn('category_id', array_merge($categorie_ids, $categories->pluck('id')->toArray()))
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        //load more articles ...
        if (request()->ajax() || request('debug')) {
            foreach ($articles as $article) {
                $article->fillForJs();
            }
            return $articles;
        }

        $categories       = get_top_categoires($categories);
        $data             = (object) [];
        $data->categories = $categories;

        //get sticks and filter sticks ....
        $total        = $articles->total();
        $sticks       = new Collection(get_stick_articles('发现'));
        $data->sticks = $sticks;
        $articles     = $articles->filter(function ($article) use ($sticks) {
            return !in_array($article->id, $sticks->pluck('id')->toArray());
        });
        $data->articles = new LengthAwarePaginator(new Collection($articles), $total, 10);

        $data->carousel = get_top_articles();

        $data->videos = Video::orderBy('id', 'desc')->where('status', '>', 0)->take(4)->get();

        return view('index.index')
            ->withData($data);
    }

    public function app()
    {
        if (file_exists(resource_path('views/index/apps/' . get_domain_key() . '.blade.php'))) {
            return view('index.apps.' . get_domain_key());
        } else {
            return "app下载页面正在制作中...";
        }
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
            $articles = Article::where('status', '>', 0)
                ->orderBy('hits', 'desc')
                ->paginate(10);
        }

        return view('index.trending')->withArticles($articles);
    }
}
