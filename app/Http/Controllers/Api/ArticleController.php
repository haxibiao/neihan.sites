<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Image;
use App\Query;
use App\User;
use App\Comment;
use App\Like;
use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ArticleController extends Controller
{
    public function fakeUsers()
    {
        return User::where('is_editor', 1)->get();
    }

    public function import(Request $request)
    {
        $data     = $request->get('data');
        $jsonData = json_decode($data, true);
        $user_id  = $jsonData['user_id'];

        $category = $jsonData['category'];

        //category

        $default_import_category_name = config('seo.default_import_category_name');
        if (empty($default_import_category_name)) {
            $category = Category::firstOrnew([
                'name'    => $category['name'],
                'name_en' => $category['name_en'],
            ]);
        } else {
            $category = Category::where('name', $default_import_category_name)->first();
        }

        // if (!$category->id)
        {
            $category->user_id = $user_id;
            $category->status  = 1;
            $category->type    = 'article';
            $category->save();

            //category admin
            $category->admins()->syncWithoutDetaching([$user_id => ['is_admin' => 1]]);
        }

        //article
        $article = Article::firstOrnew([
            'source_url' => $jsonData['article']['source_url'],
        ]);
        $article->fill($jsonData['article']);
        $article->user_id     = $user_id;
        $article->category_id = $category->id;
        $article->status      = 1;
        $article->count_words = count_words($article->body);
        $article->body        = fix_article_body_images($article->body);

        //random time
        $article->updated_at = strtotime($jsonData['time']);
        $article->created_at = strtotime($jsonData['time']);
        $article->save(['timestamp' => false]);

        //images
        if (!empty($jsonData['article']['images'])) {
            foreach ($jsonData['article']['images'] as $image) {
                $image = Image::firstOrnew([
                    'path' => $image['path'],
                ]);
                $image->source_url = 'https://haxibiao.com/' . $image['path'];
                $image->title      = $article->title;
                $image->save();
                $img_ids[] = $image->id;
            }
            $article->images()->sync($img_ids);
        }
        $article->load('images');

        $article->categories()->syncwithoutDetaching([
            $category->id => [
                'submit' => '已收录',
            ],
        ]);
        $article->save(['timestamp' => false]);

        //user
        $user                 = User::findOrFail($user_id);
        $user->count_articles = $user->articles()->count();
        $user->count_words    = $user->articles()->sum('count_words');
        $user->save();

        //category
        $category->count = $category->publishedArticles()->count();
        $category->save();

        return $article;
    }

    // api 给前端 app 用
    public function index(Request $request)
    {
        $page_size     = 12;
        $page          = request('page') ? request('page') : 1;
        $query_builder = Article::with('category')
            ->orderBy('id', 'desc')
            ->where('status', '>', 0);

        //支持首页搜索
        $query = $request->get('query');
        if ($request->get('category_id')) {
            $query_builder = $query_builder->where('category_id', $request->get('category_id'));
        }
        if ($query) {
            $query_builder = $query_builder->where('title', 'like', '%' . $query . '%')
                ->orWhere('keywords', 'like', '%' . $query . '%');
        }

        $articles = $query_builder->paginate($page_size);

        $articles->getCollection()->transform(function ($article) {
            return $article->fillForApp();
        });

        $total = $articles->total();

        if ($query && !$total) {
            $controller     = new \App\Http\Controllers\SearchController();
            $articles_taged = $controller->search_tags($query);
            $total          = count($articles_taged);
            //给标签搜索到的分页
            $articles = new Paginator($articles_taged->forPage($page, $page_size), $total, $page_size, $page);
        }

        //暂时不搜索哈希表爬虫内容
        // if ($query && !$total) {
        //     $controller   = new \App\Http\Controllers\SearchController();
        //     $articles_hxb = $controller->search_hxb($query);
        //     foreach ($articles_hxb as $article) {
        //         $articles->push($article);
        //     }
        //     $total = count($articles_hxb);
        // }

        //保存搜索记录
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

    public function trash(Request $request)
    {
        $user     = $request->user();
        $articles = $user->removedArticles;
        return $articles;
    }

    public function store(Request $request)
    {
        $article          = new Article($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        //images
        $article->saveRelatedImagesFromBody();

        return $article;
    }

    public function update(Request $request, $id)
    {
        $user                 = $request->user();
        $article              = Article::findOrFail($id);
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->update($request->all());

        //images
        $article->saveRelatedImagesFromBody();

        if ($request->status == 1) {
            //可能是发布了文章，需要统计文集的文章数，字数
            foreach ($article->collections as $collection) {
                $collection->count       = $collection->articles()->count();
                $collection->count_words = $collection->articles()->sum('count_words');
                $collection->save();
            }

            //统计用户的文章数，字数
            $user->count_articles = $user->articles()->count();
            $user->count_words    = $user->articles()->sum('count_words');
            $user->save();
        }

        return $article;
    }

    public function destroy(Request $request, $id)
    {
        \DB::beginTransaction();
        try
        {
            $comments = Comment::where('comment_id','=',$id)->where('commentable_type','=','articles')->delete();
            $likes = Like::where('liked_id','=',$id)->where('liked_type','=','articles')->delete();
            $favorite = Favorite::where('faved_id','=',$id)->where('faved_type','=','articles')->delete();
            $user = \Auth::User();
            $user->decrement('count_articles');
            $result = Article::destroy($id);
            \DB::commit();
            return $result;
        }
        catch(\Exception $e)
        {
            \DB::rollBack();
            return 0;
        }
    }

    public function delete(Request $request, $id)
    {
        $article         = Article::findOrFail($id);
        $article->status = -1;
        $article->save();
        return $article;
    }

    public function restore(Request $request, $id)
    {
        $article         = Article::findOrFail($id);
        $article->status = 0;
        $article->save();

        //如果文集也被删除了，恢复出来
        foreach ($article->collections as $collection) {
            if ($collection->status == -1) {
                $collection->status = 0;
                $collection->save();
            }
        }

        return $article;
    }

    public function likes(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $likes   = $article->likes()->with('user')->paginate(10);
        foreach ($likes as $like) {
            $like = $like->createdAt();
        }
        return $likes;
    }

    public function remove_inline_styles($body)
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

    public function show($id)
    {
        $article            = Article::with('user')->with('category')->with('images')->findOrFail($id);
        $article->image_url = get_full_url($article->image_url);
        if ($article->category) {
            $article->category->logo = get_full_url($article->category->logo);
        }

        $controller         = new \App\Http\Controllers\ArticleController();
        $article->connected = $controller->get_json_lists($article);
        $article->similar   = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();
        foreach ($article->similar as $similar_article) {
            $similar_article->body = $this->remove_inline_styles($similar_article->body);
        }

        $article->body    = $this->remove_inline_styles($article->body);
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
}
