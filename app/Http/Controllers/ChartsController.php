<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function ajax($type)
    {
        if ($type == 'new') {
            $articles = Article::orderBy('id', 'desc')->where('status',1)->with('user')->with('category')->paginate(10);
            //load more articles ...
            if (request()->ajax() || request('debug')) {
                foreach ($articles as $article) {
                    $article->fillForJs();
                }
            }
        } else if ($type == 'seven') {
            $articles = Article::orderBy('hits', 'desc')
                ->where('status', 1)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-7))
                ->with('category')
                ->with('user')
                ->paginate(10);
            if (request()->ajax() || request('debug')) {
                foreach ($articles as $article) {
                    $article->fillForJs();
                }
            }
        } else if ($type == 'thirty') {
            $articles = Article::orderBy('hits', 'desc')
                ->where('status', 1)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-30))
                ->with('category')
                ->with('user')
                ->paginate(10);
            if (request()->ajax() || request('debug')) {
                foreach ($articles as $article) {
                    $article->fillForJs();
                }
            }
        }
        return $articles;
    }
}
