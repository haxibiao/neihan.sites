<?php

namespace App\Http\Controllers;

use App\Article;
use App\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query    = $request->get('q');
        $articles = Article::where('title', 'like', '%' . $query . '%')
            ->orWhere('keywords', 'like', '%' . $query . '%')
            ->paginate(10);
        $data['articles'] = $articles;
        $total            = $articles->total();
        if ($articles->isEmpty()) {
            $articles_hxb = $this->search_hxb($query);
            foreach ($articles_hxb as $article) {
                $article->created_at  = \Carbon\Carbon::parse($article->created_at);
                $article->updated_at  = \Carbon\Carbon::parse($article->updated_at);
                $article->description = str_limit(strip_tags($article->body), 250);
                $article->image_url   = get_full_url($article->image_url);
                $articles->push($article);
            }
            $total = count($articles_hxb);
        }
        if (!empty($query) && !$articles->isEmpty()) {
            $query_item = Query::firstOrNew([
                'query' => $query,
            ]);
            $query_item->results = $total;
            $query_item->hits++;
            $query_item->save();
        }
        $queries         = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->take(20)->get();
        $data['queries'] = $queries;
        $data['query']   = $query;
        $data['total']   = $total;

        return view('search')->withData($data);
    }

    public function search_hxb($query)
    {
        $results = Cache::get('query_' . $query);
        if ($results) {
            return $results;
        }

        $api_url = 'http://haxibiao.com/api/articles';
        $api_url .= '?query=' . $query;
        $api_url .= "&page=" . $this->get_page();
        $json         = file_get_contents($api_url);
        $json_data    = json_decode($json);
        $articles_hxb = collect($json_data->data);
        Cache::put('query_' . $query, $articles_hxb, 60 * 24);
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
}
