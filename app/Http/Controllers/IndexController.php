<?php

namespace App\Http\Controllers;

use App\Article;
use App\Query;
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
                ->where('status', '>', 0)
                ->where('image_url', '<>', '')
                ->take(2)
                ->get();
            $data[$cate->name_en] = [
                'name'     => $cate->name,
                'articles' => $articles,
            ];
        }

        $carousel_items = get_carousel_items();
        $users          = User::orderBy('id', 'desc')->take(5)->get();
        $hot_articles   = Article::orderBy('hits', 'desc')->take(2)->get();

        $queries = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->take(10)->get();

        $queries = $queries->filter(function ($item) {
            $has_badword = false;
            foreach (get_badwords() as $badword) {
                if (str_contains($item->query, $badword)) {
                    $has_badword = true;
                }
            }
            return !$has_badword;
        });

        $queries_new = Query::where('status', '>=', 0)->orderBy('id', 'desc')->take(5)->get();
        //过滤最近查询里的badword
        $queries_new = $queries_new->filter(function ($item) {
            $has_badword = false;
            foreach (get_badwords() as $badword) {
                if (str_contains($item->query, $badword)) {
                    $has_badword = true;
                }
            }
            return !$has_badword;
        });

        return view('index.index')
            ->withQueries($queries)
            ->withQueriesNew($queries_new)
            ->withCarouselItems($carousel_items)
            ->withUsers($users)
            ->withHotArticles($hot_articles)
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
}
