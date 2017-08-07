<?php

namespace App\Http\Controllers;

use App\Article;
use App\Query;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query    = $request->get('q');
        $articles = Article::where('title', 'like', '%' . $query . '%')
            ->orWhere('keywords', 'like', '%' . $query . '%')
            ->paginate(10);
        $data['articles'] = $articles;
        if (!empty($query) && !$articles->isEmpty()) {
            $query_item = Query::firstOrNew([
                'query' => $query,
            ]);
            $query_item->results = $articles->total();
            $query_item->hits++;
            $query_item->save();
        }
        $queries = Query::where('status','>=', 0)->orderBy('hits','desc')->take(20)->get();
        $data['queries'] = $queries;
        $data['query'] = $query;

        return view('search')->withData($data);
    }
}
