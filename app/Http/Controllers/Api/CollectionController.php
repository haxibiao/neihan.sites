<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $user        = $request->user();
        $collections = $user->collections()->with('articles')->orderBy('id', 'desc')->get(); 
        return $collections; 
    }

    public function show(Request $request, $id)
    {
        return Collection::findOrFail($id);
    }

    public function articles(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);
        $articles   = $collection->articles()->with('user')->orderBy(request('collected') ? 'created_at' : 'updated_at', 'desc')->paginate(10);
        foreach ($articles as $article) {
            $article->user        = $article->user->fillForJs();
            $article->description = $article->description();
        }
        return $articles;
    }

    public function create(Request $request)
    {
        $collection          = new Collection($request->all());
        $collection->user_id = $request->user()->id;
        $collection->save();
        $collection->load('articles');
        return $collection;
    }

    public function update(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);
        $collection->update($request->all());
        return $collection;
    }

    public function delete(Request $request, $id)
    {
        $collection         = Collection::findOrFail($id);
        $collection->status = -1;
        $collection->save();

        //delete articles to trash
        foreach ($collection->articles as $article) {
            $article->status = -1;
            $article->save();
        }

        return $collection;
    }

    public function moveArticle(Request $request, $id, $cid)
    {
        $article = Article::findOrFail($id);
        $article->collection_id = $cid;
        $article->timestamps = false;
        $article->save();

        return $article;
    }

    public function createArticle(Request $request, $id)
    {
        $article          = new Article($request->all());
        $article->user_id = $request->user()->id;
        $article->collection_id = $id;
        $article->save();

        //暂时维护个和文集的多对多关系，方便今后文集之间复制文章的时候用
        // $article->collections()->sync($id);

        return $article;
    }
}
