<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleImage;
use App\ArticleTag;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Image;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.editor')->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Article::orderBy('id', 'desc')
            ->where('status', '>=', 0);
        if (!Auth::user()->is_admin) {
            $query = $query->where('user_id', Auth::user()->id);
        }
        $articles = $query->paginate(10);
        return view('article.index')->withArticles($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = get_categories();
        return view('article.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $article              = new Article();
        $article->title       = $request->get('title');
        $article->keywords    = $request->get('keywords');
        $article->description = $request->get('description');
        $article->author      = $request->get('author');
        $article->user_id     = $request->get('user_id');
        $article->category_id = $request->get('category_id');
        $article->body        = $request->get('body');
        $article->image_url   = $request->get('image_url');
        $article->has_pic  = !empty($article->image_url);
        $article->save();

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
        $this->save_article_images($imgs, $article);

        return redirect()->to('/article/' . $article->id);
    }

    public function save_article_images($imgs, $article)
    {
        if (is_array($imgs)) {
            foreach ($imgs as $img) {
                $path  = parse_url($img)['path'];
                $image = Image::firstOrNew([
                    'path' => $path,
                ]);
                $image->count = $image->count + 1;
                $image->save();

                $article_image = ArticleImage::firstOrNew([
                    'article_id' => $article->id,
                    'image_id'   => $image->id,
                ]);
                $article_image->save();
            }
        }
    }

    public function save_article_tags($article)
    {
        $keywords = preg_split("/(#|:|,|，|\s)/", $article->keywords);
        foreach ($keywords as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $tag = Tag::firstOrNew([
                    'name' => $word,
                ]);
                $tag->user_id = Auth::user()->id;
                $tag->save();

                $article_tag = ArticleTag::firstOrNew([
                    'article_id' => $article->id,
                    'tag_id'     => $tag->id,
                ]);
                $article_tag->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article       = Article::with('user')->with('category')->with('tags')->with('images')->findOrFail($id);
        $article->body = str_replace("\n", '<br/>', $article->body);

        return view('article.show')->withArticle($article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article    = Article::findOrFail($id);
        $categories = get_categories();
        return view('article.edit')->withArticle($article)->withCategories($categories);
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
        $article = Article::findOrFail($id);
        $article->update($request->all());
        $article->has_pic = !empty($article->image_url);
        $article->save();

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
        $this->save_article_images($imgs, $article);

        return redirect()->to('/article/' . $article->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        if ($article) {
            $article->delete();
        }

        return redirect()->back();
    }
}
