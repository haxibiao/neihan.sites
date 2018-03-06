<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Traits\ArticleControllerFunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
    //除了curd相关函数全都被我抽出去了
    use ArticleControllerFunction
    ;

    public function __construct()
    {
        $this->middleware('auth.editor')->except('show', 'article_new');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('draft')) {
            $articles = Article::orderBy('id', 'desc')->where('status', 0)->paginate(10);
            if (!Auth::user()->is_admin) {
                $articles = Auth::user()->articles()->where('status', 0)->orderBy('id', 'desc')->paginate(10);
            }

            return view('article.index')->withArticles($articles);
        }

        $articles = Article::orderBy('id', 'desc')->where('status', '>', 0)->paginate(10);
        if (!Auth::user()->is_admin) {
            $articles = Auth::user()->articles()->where('status', '>', 0)->orderBy('id', 'desc')->paginate(10);
        }
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

        }
        $category_ids     = request('category_ids');
        $article->has_pic = !empty($article->image_url);
        $article->date    = \Carbon\Carbon::now()->toDateString();
        if (is_array($category_ids) && !empty($category_ids)) {
            $article->category_id = max($category_ids);
        }
        //简单计算该文章有多少个字
        $article->words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->body  = $this->fix_body($article->body);
        $article->save();

        //categoies
        $article->categories()->sync($request->get('category_ids'));

        //videos
        $this->save_article_videos($request, $article);

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $this->get_image_urls_from_body($article->body);
        $this->auto_upadte_image_relations($imgs, $article);

        //is_top
        $this->article_is_top($request, $article);

        //delay
        $this->article_delay($request, $article);

        //music
        $this->save_article_music($request, $article);

        //defalut 默认会被收录
        $this->category_article_submit($request, $article);

        //count article
        $this->article_count($request->get('category_ids'), $article);
        //count user
        $this->article_user_count($article->words);

        return redirect()->to('/article/' . $article->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with('user')
        ->with('category')
        ->with('tags')
        ->with('images')
        ->with('comments')
        ->findOrFail($id);

        if (!empty($article->category) && $article->category->parent_id) {
            $data['parent_category'] = $article->category->parent()->first();
        }

        if ($article->status < 0) {
            return "该文章不存在或者已经删除。。。。";
        }

        $article->hits = $article->hits + 1;
        $agent         = new \Jenssegers\Agent\Agent();

        if ($agent->isMobile()) {
            $article->hits_mobile = $article->hits_mobile + 1;
        }
        if ($agent->isPhone()) {
            $article->hits_phone = $article->hits_phone + 1;
        }
        if ($agent->match('micromessenger')) {
            $article->hits_wechat = $article->hits_wechat + 1;
        }
        if ($agent->isRobot()) {
            $article->hits_robot = $article->hits_robot + 1;
        }
        $article->save();

        //fix_article_count

        $this->article_coment_count($article);

        //fix for show
        $article->body = $this->fix_body($article->body);

        $article->body = parse_video($article->body);

        $data['json_lists'] = $this->get_json_lists($article);
        $data['related']    = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();
        if (str_contains($article->keywords, '王者荣耀')) {
            fix_wz_data($article);
        }
        if (str_contains($article->keywords, '英雄联盟英雄资料')) {
            fix_lol_data($article);
        }

        return view('article.show')->withArticle($article)->withData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::with('images')->findOrFail($id);

        //fix img relation missing, 同是修复image_url对应的image_top 为主要image_top
        $imgs = $this->get_image_urls_from_body($article->body);
        if ($imgs) {
            $this->auto_upadte_image_relations($imgs, $article);
        }
        $article = Article::with('images')->findOrFail($id);

        //编辑文章的时候,可能没有插入图片,字段可能空,就会删除图片地址....
        $this->clear_article_imgs($article);

        $categories    = get_categories();
        $article->body = str_replace('<single-list id', '<single-list class="box-related-half" id', $article->body);
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

        $article->update($request->except('image_url', 'category_id'));

        $category_ids = request('category_ids');
        if (is_array($category_ids) && !empty($category_ids)) {
            $article->category_id = max($category_ids);
        }

        if ($request->get('primary_image')) {
            $article->image_url = $request->get('primary_image');
        }

        $article->has_pic   = !empty($article->image_url);
        $article->edited_at = \Carbon\Carbon::now();
        $article->body      = $this->fix_body($article->body);
        $article->save();

        $article->categories()->sync(request('category_ids'));

        //videos
        $this->save_article_videos($request, $article);

        //music
        $this->save_article_music($request, $article);

        //tags
        $this->save_article_tags($article);

        //is_top
        $this->article_is_top($request, $article);

        //delay
        $this->article_delay($request, $article);

        //images
        $imgs = $this->get_image_urls_from_body($article->body);
        $this->auto_upadte_image_relations($imgs, $article);

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
        $article = Article::findOrFail($id);
        if ($article) {
            $article->status = -1;
            $article->save();
        }

        return redirect()->back();
    }
}
