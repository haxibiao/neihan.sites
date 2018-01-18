<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Notifications\CategoryRequested;
use App\Query;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getIndex(Request $request)
    {
        $query         = $request->get('query');
        $query_builder = Article::with('category')->orderBy('id', 'desc');
        if ($request->get('category_id')) {
            $query_builder = $query_builder->where('category_id', $request->get('category_id'));
        }
        if ($query) {
            $query_builder = $query_builder->where('title', 'like', '%' . $query . '%')
                ->orWhere('keywords', 'like', '%' . $query . '%');
        }
        $articles = $query_builder->paginate(12);
        foreach ($articles as $article) {
            $article->image_url      = get_full_url($article->image_url);
            $article->user->avatar   = get_avatar($article->user);
            $article->category->logo = get_full_url($article->category->logo);
            $article->body           = $this->fix_inline_styles($article->body);
            $article->pubtime        = diffForHumansCN($article->created_at);
        }
        $total = $articles->total();

        if ($query && !$total) {
            $controller     = new \App\Http\Controllers\SearchController();
            $articles_taged = $controller->search_tags($query);
            foreach ($articles_taged as $article) {
                $articles->push($article);
            }
            $total = count($articles_taged);
        }

        if ($query && !$total) {
            $controller   = new \App\Http\Controllers\SearchController();
            $articles_hxb = $controller->search_hxb($query);
            foreach ($articles_hxb as $article) {
                $articles->push($article);
            }
            $total = count($articles_hxb);
        }

        if (!empty($query) && $total) {
            $query_item = Query::firstOrNew([
                'query' => $query,
            ]);
            $query_item->results = $total;
            $query_item->hits++;
            $query_item->save();
        }
        return $articles;
    }

    public function fix_inline_styles($body)
    {
        //fix font-size <span style="font-size: 18px;">
        $pattern = "/font-size: (\d+)px;/";
        $body    = preg_replace($pattern, "", $body);

        //fix line-height <span style="line-height: 1.6;">
        $pattern = "/line-height: (.*);/iU";
        $body    = preg_replace($pattern, "", $body);

        $body = str_replace("style=\"\"", "", $body);
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
            $similar_article->body = $this->fix_inline_styles($similar_article->body);
        }

        $article->body    = $this->fix_inline_styles($article->body);
        $article->pubtime = diffForHumansCN($article->created_at);

        return $article;
    }

    public function checkCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $user     = $request->user();
        $articles = $user->articles()->paginate(10);
        foreach ($articles as $article) {
            $query = $article->categories()->wherePivot('category_id', $id);
            if ($query->count()) {
                $article->submited_status = $query->first()->pivot->submit;
            } else {
                $article->submited_status = '';
            }
            $article->submit_status = $this->get_submit_status($article->submited_status);
        }

        return $articles;
    }

    public function submitCategory(Request $request, $aid, $cid)
    {
        $user     = $request->user();
        $article  = Article::findOrFail($aid);
        $category = Category::findOrFail($cid);

        $query = $article->categories()->wherePivot('category_id', $cid);
        if ($query->count()) {
            $pivot         = $query->first()->pivot;
            $pivot->submit = $pivot->submit == '待审核' ? '已撤回' : '待审核';
            $pivot->save();
            $article->submited_status = $pivot->submit;
        } else {
            $article->submited_status = '待审核';
            $article->categories()->syncWithoutDetaching([
                $cid => [
                    'submit' => $article->submited_status,
                ],
            ]);
        }
        if ($article->submited_status == '待审核') {
            $category->user->notify(new CategoryRequested($cid, $aid));
        }
        $category->new_requests = $category->articles()->wherePivot('submit', '待审核')->count();
        $category->save();
        $article->submit_status = $this->get_submit_status($article->submited_status);
        return $article;
    }

    public function get_submit_status($submited_status)
    {
        $submit_status = '投稿';
        switch ($submited_status) {
            case '待审核':
                $submit_status = '撤回';
                break;
            case '已收录':
                $submit_status = '移除';
                break;
            case '已拒绝':
                $submit_status = '再次投稿';
                break;
            case '已撤回':
                $submit_status = '再次投稿';
                break;

            default:
                # code...
                break;
        }

        return $submit_status;
    }

    public function approveCategory(Request $request, $aid, $cid)
    {
        $user    = $request->user();
        $article = Article::findOrFail($aid);

        $query = $article->categories()->wherePivot('category_id', $cid);
        if ($query->count()) {
            $pivot         = $query->first()->pivot;
            $pivot->submit = $request->get('deny') ? '已拒绝' : '已收录';
            $pivot->save();
            $article->submited_status = $pivot->submit;
        }
        $article->submit_status = $this->get_submit_status($article->submited_status);
        return $article;
    }

    public function show($id)
    {
        $article                 = Article::with('user')->with('category')->findOrFail($id);
        $article->image_url      = get_full_url($article->image_url);
        $article->category->logo = get_full_url($article->category->logo);

        $controller         = new \App\Http\Controllers\ArticleController();
        $article->connected = $controller->get_json_lists($article);
        $article->similar   = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();
        foreach ($article->similar as $similar_article) {
            $similar_article->body = $this->fix_inline_styles($similar_article->body);
        }

        $article->body    = $this->fix_inline_styles($article->body);
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
                                'image_url' => $article->primaryImage(),
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

    public function addCategory(Request $request, $aid, $cid)
    {
        $user=$request->user();
        $article =Article::findOrFail($aid);
        $category=Category::findOrFail($cid);

        $query =$category->articles()->wherePivot('article_id',$aid);
        if ($query->count()) {
            $pivot         = $query->first()->pivot;
            $pivot->submit = $pivot->submit == '已收录' ? '已撤回' : '已收录';
            $pivot->save();
            $category->submited_status = $pivot->submit;
        } else {
            $category->articles()->syncWithoutDetaching([
                $aid => [
                    'submit' => '已收录',
                ],
            ]);
          $category->submited_status = '已收录';

           // $article->user->notify(new CategoryCollected($cid, $aid));
        }

      $category->submit_status =$category->submited_status== '已收录' ? '移除' : '收录';
      return $category;
    }

    public function adminCategoriesCheckArticle(Request $request,$aid){
        $user=$request->user();

        $qb = $user->adminCategories()->with('user');

        if (request('q')) {
            $qb = $qb->where('categories.name', 'like', request('q') . '%');
        }

        $categories =$qb->paginate(12);

        //get article status
        foreach($categories as $category){
            $category->submited_status='';
            $query                     = $category->articles()->wherePivot('article_id', $aid);
            if ($query->count()) {
                $category->submited_status = $query->first()->pivot->submit;
            }
            $category->submit_status =$category->submited_status=='已收录' ? '移除' : '收录';
        }

        return $categories;
    }
}
