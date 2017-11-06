<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleTag;
use App\Query;
use App\Tag;
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

        if (!$total) {
            $articles_taged = $this->search_tags($query);
            foreach ($articles_taged as $article) {
                $articles->push($article);
            }
            $total = count($articles_taged);
        }

        if (!$total) {
            $articles_hxb = $this->search_hxb($query);
            foreach ($articles_hxb as $article) {
                $article->created_at  = \Carbon\Carbon::parse($article->created_at);
                $article->updated_at  = \Carbon\Carbon::parse($article->updated_at);
                $article->description = str_limit(strip_tags($article->body), 250);
                $article->image_url   = get_full_url($article->image_url);
                $article->target_url  = "http://haxibiao.com/article/" . $article->id;
                $articles->push($article);
            }
            $total = count($articles_hxb);
        }

        if (!empty($query) && $total) {
            if (!in_array($query, get_badwords())) {
                $query_item = Query::firstOrNew([
                    'query' => $query,
                ]);
                $query_item->results = $total;
                $query_item->hits++;
                $query_item->save();
            }
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
                $ats = ArticleTag::with('article')->where('tag_id', $tag->id)->get();
                foreach ($ats as $at) {
                    if (!empty($at->article)) {
                        $articles_taged[] = $at->article;
                    }

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
}
