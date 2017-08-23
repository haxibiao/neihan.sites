<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getIndex(Request $request)
    {
        $query_builder = Article::with('category')->orderBy('id', 'desc');
        if ($request->get('category_id')) {
            $query_builder = $query_builder->where('category_id', $request->get('category_id'));
        }
        if ($query = $request->get('query')) {
            $query_builder = $query_builder->where('title', 'like', '%' . $query . '%')
                ->orWhere('keywords', 'like', '%' . $query . '%');
        }
        $articles = $query_builder->paginate(12);
        foreach ($articles as $article) {
            $article->image_url      = get_full_url($article->image_url);
            $article->user->avatar   = get_avatar($article->user);
            $article->category->logo = get_full_url($article->category->logo);
            $article->body           = $this->fix_font_size($article->body);
            $article->pubtime        = diffForHumansCN($article->created_at);
        }

        if ($query = $request->get('query')) {
            $controller = new \App\Http\Controllers\SearchController();
            if ($articles->isEmpty()) {                
                $articles_hxb = $controller->search_hxb($query);
                foreach($articles_hxb as $article) {
                    $articles->push($article);
                }
            }
        }
        return $articles;
    }

    public function fix_font_size($body)
    {
        //fix font-size <span style="font-size: 18px;">
        $pattern = "/font-size: (\d+)px;/";
        $body    = preg_replace($pattern, "", $body);
        return $body;
    }

    public function getShow($id)
    {
        $article                 = Article::with('user')->with('category')->findOrFail($id);
        $article->image_url      = get_full_url($article->image_url);
        $article->user->avatar   = get_avatar($article->user);
        $article->category->logo = get_full_url($article->category->logo);

        $controller         = new \App\Http\Controllers\ArticleController();
        $article->connected = $controller->get_json_lists($article);
        $article->similar   = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();
        foreach ($article->similar as $similar_article) {
            $similar_article->body = $this->fix_font_size($similar_article->body);
        }

        $article->body    = $this->fix_font_size($article->body);
        $article->pubtime = diffForHumansCN($article->created_at);

        return $article;
    }

    public function saveRelation(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $data    = json_decode($article->json, true);
        if (empty($data)) {
            $data = [];
        }
        $data[]        = $request->all();
        $article->json = json_encode($data);
        $article->save();

        //同时更新被关联文章的默认关联集合
        foreach ($request->get('aids') as $aid) {
            $article_connected = Article::find($aid);
            $json_data         = json_decode($article_connected->json, true);
            if (empty($json_data)) {
                $json_data = [];
            }
            $exist_item = null;
            $exist_key  = 0;
            foreach ($json_data as $key => $item) {
                if (!empty($item['title']) && $item['title'] == "本文正被其他文章引用") {
                    $exist_item = $item;
                    $exist_key  = $key;
                }
            }
            if (!$exist_item) {
                $connect_item = [
                    'col'   => 'col-md-6',
                    'title' => "本文正被其他文章引用",
                    'aids'  => [$id],
                ];
                $json_data[] = $connect_item;
            } else {
                if (!in_array($id, $exist_item['aids'])) {
                    $exist_item['aids'][] = $id;
                    if (count($exist_item['aids']) >= 3) {
                        $exist_item['col'] = 'col-md-12';
                    } else {
                        $exist_item['col'] = 'col-md-6';
                    }
                    $json_data[$exist_key] = $exist_item;
                }
            }
            $article_connected->json = json_encode($json_data);
            $article_connected->save();
        }

        return $article;
    }

    public function getAllRelations(Request $request, $id)
    {
        $article   = Article::findOrFail($id);
        $contoller = new \App\Http\Controllers\ArticleController();
        return $contoller->get_json_lists($article);
    }

    public function deleteRelation(Request $request, $id, $key)
    {
        $article = Article::findOrFail($id);
        $data    = json_decode($article->json, true);
        if (empty($data)) {
            $data = [];
        }
        $data_new = [];
        foreach ($data as $k => $list) {
            if ($k == $key) {
                continue;
            }
            $data_new[] = $list;
        }

        $article->json = json_encode($data_new);
        $article->save();

        //TODO:: 删除被引用文章的关系

        return $data_new;
    }

    public function getRelation($id, $key)
    {
        $article = Article::findOrFail($id);
        $json    = json_decode($article->json, true);
        if (array_key_exists($key, $json)) {
            $data = $json[$key];
            if (empty($data['type']) || $data['type'] == 'single_list') {
                $items = [];
                if (is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $article = Article::find($aid);
                        if ($article) {
                            $items[] = [
                                'id'        => $article->id,
                                'title'     => $article->title,
                                'image_url' => get_small_image($article->image_url),
                            ];
                        }
                    }
                }
                $data['items'] = $items;
            }

            return $data;
        }
        return null;
    }
}
