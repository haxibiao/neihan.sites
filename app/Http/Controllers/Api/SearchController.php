<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Collection;
use App\Http\Controllers\Controller;
use App\Query;
use App\User;
use App\Tag;
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

    public function search(Request $request)
    {
        $type    = $request->type;
        $query   = $request->get('query');
        $user_id = $request->get('user_id');
        $order   = $request->get('order');

        $this->save_user_serachHistory($user_id, $query);

        if ($type == "note") {
            return empty($order)?$this->search_article($query):$this->search_article($query,$order);
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

    public function search_article($query,$order='id')
    {
        $articles = Article::with('user')
            ->whereHas('tags',function($q) use($query){
                $q->where('name','like','%'.$query.'%');
            })
            ->orWhere('title', 'like', '%' . $query . '%')
            ->orWhere('body', 'like', '%' . $query . '%')
            ->orderBy($order, 'desc')
            ->paginate(5)
        ;

        if($articles->isEmpty()){
            return;
        }
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
        $history   = Cache::get($cache_key);

        if (is_array($history) && !in_array($query, $history)) {
            array_push($history, $query);
            Cache::put($cache_key, $history, 24 * 60 * 3);
        } elseif ($history == null) {
            $data = [];
            array_push($data, $query);
            Cache::put($cache_key, $data, 24 * 60 * 3);
        }
    }

    public function get_user_histroy(Request $request, $id)
    {
        $cache_key = $id . 'searchHistory';
        $historys  = Cache::get($cache_key);

        return $historys;
    }

    public function clear_user_history(Request $request, $id, $history)
    {
        $cache_key = $id . 'searchHistory';
        $historys  = Cache::get($cache_key);

        if (is_array($historys) && in_array($history, $historys)) {
            $index = array_search($history, $historys);
            if ($index >= 0) {
                array_splice($historys, $index, 1);
                Cache::put($cache_key, $historys, 24 * 60 * 3);
            } else {
                return response($content = '没有找到该条历史记录', $status = 404);
            }
        }
    }
}
