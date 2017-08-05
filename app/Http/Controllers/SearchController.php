<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query            = $request->get('q');
        $data['articles'] = Article::where('title', 'like', '%' . $query . '%')
        	->orWhere('keywords', 'like', '%' . $query . '%')
        	->paginate(10);
        $data['query']    = $query;

        return view('search')->withData($data);
    }
}
