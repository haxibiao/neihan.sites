<?php

namespace App\Http\Controllers;

use App\Article;
use App\Query;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query    = $request->get('q');
        $articles = Article::where('title', 'like', '%' . $query . '%')
            ->orWhere('keywords', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['articles'] = $articles;
        $total            = $articles->total();

        if (!$total) {
            $articles_taged = $this->search_tags($query);
            foreach ($articles_taged as $article) {
                $articles->push($article);
            }
            $total = count($articles_taged);
        }

        //暂时不关联搜索哈希表里的文章了，后面优化下文章结构后再同步搜索

        // if (!$total) {
        //     $articles_hxb = $this->search_hxb($query);
        //     foreach ($articles_hxb as $article) {
        //         $article->created_at  = \Carbon\Carbon::parse($article->created_at);
        //         $article->updated_at  = \Carbon\Carbon::parse($article->updated_at);
        //         $article->description = str_limit(strip_tags($article->body), 250);
        //         $article->image_url   = get_full_url($article->image_url);
        //         $article->target_url  = "http://haxibiao.com/article/" . $article->id;
        //         $articles->push($article);
        //     }
        //     $total = count($articles_hxb);
        // }

        if (!empty($query) && $total) {
            $query_item = Query::firstOrNew([
                'query' => $query,
            ]);
            $query_item->results = $total;
            $query_item->hits++;
            $query_item->save();
        }
        $data['queries']     = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->take(20)->get();
        $data['queries_new'] = Query::where('status', '>=', 0)->orderBy('id', 'desc')->take(20)->get();
        $data['query']       = $query;
        $data['total']       = $total;

        return view('search')->withData($data);
    }

    public function search_tags($query)
    {
        $articles_taged = [];
        $tags           = Tag::all();
        foreach ($tags as $tag) {
            if (str_contains($query, $tag->name)) {
                foreach ($tag->articles as $article) {
                    $articles_taged[] = $article;
                }
            }
        }

        return $articles_taged;
    }

    public function search_hxb($query)
    {
        $results = Cache::get('query_' . $query);
        if ($results) {
            return $results;
        }
        $articles_hxb = [];
        $api_url      = 'http://haxibiao.com/api/articles';
        $api_url .= '?query=' . $query;
        $api_url .= "&page=" . $this->get_page();
        if ($json = @file_get_contents($api_url)) {
            $json_data    = json_decode($json);
            $articles_hxb = collect($json_data->data);
            Cache::put('query_' . $query, $articles_hxb, 60 * 24);
        }
        return $articles_hxb;
    }

    public function get_page()
    {
        $page = 1;
        if (request('page')) {
            $page = request('page');
        }
        return $page;
    }
    public function search_all()
    {
        $users          = User::all();
        $querys         = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->paginate();
        $data           = [];
        $data['update'] = Query::where('status', '>=', 0)->orderBy('updated_at', 'desc')->paginate(10);
        return view('parts.search_all')
            ->withData($data)
            ->withUsers($users)
            ->withQuerys($querys);
    }

    public function new_search(Request $request)
    {
         $query =$request->q;
         
         return view('search.index')->withQuery($query);
    }
}