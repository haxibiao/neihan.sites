<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user        = $request->user();
        $collections = $user->collections()->with('articles')->where('status', '>=', 0)
            ->orderBy('id', 'desc')->get();
        return $collections;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $collection          = new Collection($request->all());
        $collection->user_id = $request->user()->id;
        $collection->save();
        $collection->load('articles');
        return $collection;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        //delete collection articles
        foreach ($collection->articles as $article) {
            $article->status = -1;
            $article->save();
        }

        return $collection;
    }

    public function moveArticle(Request $request, $id, $cid)
    {
        $article = Article::findOrFail($id);
        $article->collections()->sync($cid);
        return $article;
    }

    public function createArticle(Request $request, $id)
    {
        $article          = new Article($request->all());
        $article->user_id = $request->user()->id;
        $article->save();
        $article->collections()->sync($id);
        return $article;
    }
}
