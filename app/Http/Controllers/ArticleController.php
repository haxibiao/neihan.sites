<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleImage;
use App\ArticleTag;
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
        $article = new Article($request->all());

        if ($request->get('primary_image')) {
            $article->image_url = $request->get('primary_image');
        } else {
            $article->image_url = $request->get('image_url');
        }
        $article->has_pic = !empty($article->image_url);
        $article->save();

        //image_top
        $this->get_top_pic($request, $article);

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
        $this->save_article_images($imgs, $article);

        return redirect()->to('/article/' . $article->id);
    }

    function get_top_pic($request, $article) {
        $file = $request->file('image_top');
        if ($article->is_top && $file) {
            $local_path = public_path('storage/top/');
            if (!is_dir($local_path)) {
                mkdir($local_path, 0777, 1);
            }
            $filename = $article->id . '.jpg';
            $file->move($local_path, $filename);
            //resize
            $full_path = $local_path . $filename;
            $img       = \ImageMaker::make($full_path);
            $img->resize(900, 500);
            $img->save($full_path);

            $article->image_top = '/storage/top/' . $filename;
            $article->save();
        }
    }

    public function save_article_images($imgs, $article)
    {
        $article_with_images = Article::with('images')->find($article->id);
        $images              = $article_with_images->images;

        // remove not using images relationship ...
        $pattern_img = '/<img(.*?)>/';
        preg_match_all($pattern_img, $article->body, $matches);
        if (!empty($matches)) {
            foreach ($images as $image) {
                $item_exist_in_body = false;
                foreach ($matches[0] as $img_tag) {
                    if (str_contains($img_tag, $image->path)) {
                        $item_exist_in_body = true;
                    }
                }
                if (!$item_exist_in_body) {
                    $article_image = ArticleImage::firstOrNew([
                        'article_id' => $article->id,
                        'image_id'   => $image->id,
                    ]);
                    if ($article_image->id) {
                        $image->count = $image->count - 1;
                        $image->save();
                        $article_image->delete();
                    }
                }
            }
        }

        if (is_array($imgs)) {
            foreach ($imgs as $img) {
                $path  = parse_url($img)['path'];
                $path  = str_replace('.small.jpg', '', $path);
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

        //删除文章不用的关键词关系
        $article_with_tags = Article::with('tags')->find($article->id);
        $tags              = $article_with_tags->tags;
        foreach ($tags as $tag) {
            if (!in_array($tag->name, $keywords)) {
                $article_tag = ArticleTag::firstOrNew([
                    'article_id' => $article->id,
                    'tag_id'     => $tag->id,
                ]);
                $article_tag->delete();
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
        $article->hits = $article->hits + 1;
        $agent = new \Jenssegers\Agent\Agent();
        if($agent->isMobile()) {
            $article->hits_mobile = $article->hits_mobile + 1;
        }
        if($agent->isPhone()) {
            $article->hits_phone = $article->hits_phone + 1;
        }
        if($agent->match('micromessenger')) {
            $article->hits_wechat = $article->hits_wechat + 1;
        }
        if($agent->isRobot()) {
            $article->hits_robot = $article->hits_robot + 1;
        }
        $article->save();
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
        $article    = Article::with('images')->findOrFail($id);
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

        //编辑文章的时候,可能没有插入图片,字段可能空,就会删除图片地址....
        $article->update($request->except('image_url'));
        if ($request->get('primary_image')) {
            $article->image_url = $request->get('primary_image');
        } else {
            if ($request->get('image_url')) {
                $article->image_url = $request->get('image_url');
            }
        }

        $article->has_pic = !empty($article->image_url);
        $article->save();

        //image_top
        $this->get_top_pic($request, $article);

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
