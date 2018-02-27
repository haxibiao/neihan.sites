<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Collection;
use App\Http\Controllers\Controller;
use App\Query;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function hotQueries()
    {
        $queries     = Query::where('status', '>=', 0)->orderBy('hits', 'desc')->paginate(10)->pluck('query');
        $hot_queries = [];

        foreach ($queries as $query) {
            $q['full']     = $query;
            $q['short']    = str_limit($query, 14, '');
            $hot_queries[] = $q;
        }
        return $hot_queries;
    }

    public function serach(Request $request)
    {
        $type    = $request->type;
        $query   = $request->get('query');
        $user_id = $request->get('user_id');

        $this->save_user_serachHistory($user_id, $query);

        if ($type == "note") {
            return $this->search_article($query);
        }

        if ($type == 'user') {
            return $this->search_user($query);
        }

        if ($type == 'collection') {
            return $this->serach_collection($query);
        }

        if ($type == 'notebook') {
            return $this->search_notebook($query);
        }

    }

    public function search_article($query)
    {
        $articles = Article::with('user')
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('body', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->paginate(5)
        ;

        //处理数据
        foreach ($articles as $article) {
            $article->user->avatar = $article->user->getLatestAvatar();
            // $article->created_at= diffForHumansCN($article->created_at);
            $article->title       = str_replace($query, '<em class="search_result_highlight" >' . $query . '</em>', $article->title);
            $article->description = str_replace($query, '<em class="search_result_highlight" >' . $query . '</em>', $article->introduction());

            $users[]      = $article->user;
            $categories[] = $article->category;
        }

        $users      = $this->unique($users);
        $categories = $this->unique($categories);

        foreach ($users as $user) {
            $user->avatar = $user->checkAvatar();
        }

        $this->process_querys($query, $articles->total());

        $data['users'] = $users;

        $data['articles'] = $articles;

        $data['categories'] = $categories;

        return $data;
    }

    public function unique($object)
    {
        $object = array_unique($object);
        if (count($object) > 3) {
            $object = array_slice($object, 0, 3);
        }
        return $object;
    }

    public function search_user($query)
    {
        $users = User::
            where('name', 'like', '%' . $query . '%')
            ->orWhere('qq', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->paginate(5);

        $this->process_querys($query, $users->total());

        foreach ($users as $user) {
            $user->avatar = $user->checkAvatar();
        }

        return $users;
    }

    public function serach_collection($query)
    {
        $categories = Category::where('name', 'like', '%' . $query . '%')
            ->orWhere('name_en', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->paginate(5);

        $this->process_querys($query, $categories->total());

        return $categories;
    }

    public function search_notebook($query)
    {
        $collections = Collection::where('name', 'like', '%' . $query . '%')
            ->paginate(5)
        ;

        $this->process_querys($query, $collections->total());

        return $collections;
    }

    public function process_querys($query, $total)
    {

        if (!empty($query) && $total) {
            $query_item = Query::firstOrNew([
                'query' => $query,
            ]);
            $query_item->results = $total;
            $query_item->hits++;
            $query_item->save();
        }
    }

    public function save_user_serachHistory($user_id, $query)
    {
        $cache_key = $user_id . 'searchHistory';

        if(cache::get($cache_key)){
           $history=cache::get($cache_key);
           cache::put($cache_key, $history.','.$query);
        }
        cache::put($cache_key, $query);
    }

    public function get_user_histroy(Request $request, $id)
    {
        $cache_key = $id . 'searchHistory';
        $historys  = cache::get($cache_key);
        if(str_contains($historys,',')){
            $historys = explode(',', $historys);
        }

        return $historys;
    }

    public function clear_user_history(Request $request, $id)
    {
        $cache_key = $id . 'searchHistory';
        cache::forget($cache_key);
    }
}
