<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getIndex(Request $request)
    {
        $query = Article::orderBy('id', 'desc');
        if ($request->get('query')) {
            $query = $query->where('title', 'like', '%' . $request->get("query") . '%');
        }
        return $query->paginate(12);
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
