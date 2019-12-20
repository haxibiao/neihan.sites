<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Jobs\DelayArticle;
use App\Tag;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show','shareVideo');
        $this->middleware('auth.editor')->except('index', 'show', 'storePost', 'edit', 'destroy','shareVideo'); //编辑自己的文章的时候，无需编辑身份
    }

    /**
     * @Desc     发布一篇动态
     * @DateTime 2018-07-20
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function storePost(Request $request)
    {
        $article = new Article();
        $article->createPost($request->all());
        $article->saveCategories($request->get('categories'));
        return redirect()->to($article->url);
    }

    public function drafts(Request $request)
    {
        $query = Article::orderBy('id', 'desc')
            ->where('status', 0)
            ->whereType('article');
        if (!Auth::user()->is_admin) {
            $query = $query->where('user_id', Auth::user()->id);
        }
        $articles = $query->paginate(10);
        return view('article.drafts')->withArticles($articles);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Article::orderBy('id', 'desc')->where('status', '>', 0)->whereType('article');
        //Search Articles
        $data['keywords'] = '';
        if ($request->get('q')) {
            $keywords         = $request->get('q');
            $data['keywords'] = $keywords;
            $query            = Article::orderBy('id', 'desc')
                ->where('status', '>', 0)
                ->whereType('article')
                ->where('title', 'like', "%$keywords%");
        }
        if (!Auth::user()->is_admin) {
            $query = $query->where('user_id', Auth::user()->id);
        }
        $articles         = $query->paginate(10);
        $data['articles'] = $articles;
        return view('article.index')->withData($data);
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
        $user = $request->user();
        if (!str_contains($user->email, '@haxibiao.com')) {
            abort(403, '您无权发布文章');
        }
        if ($slug = $request->slug) {
            $validator = Validator::make(
                $request->input(),
                ['slug' => 'unique:articles']
            );
            if ($validator->fails()) {
                dd('当前slug已被使用');
            }
            if (is_numeric($slug)) {
                dd('slug 不能为纯数字');
            }

        }
        $article = new Article($request->all());
        $article->save();

        //delay
        $this->process_delay($article);

        //categories
        $article->saveCategories(request('categories'));

        //tags
        $this->save_article_tags($article);

        //保存外部图片
        $article->saveExternalImage();

        //images
        $article->saveRelatedImagesFromBody();

        if (!empty($article->slug)) {
            return redirect()->to('/article/' . $article->slug);
        }
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
        //此处id为中文代表slug,且$id不会是create.
        $article = Article::with(['user', 'category', 'tags', 'images'])
            ->where(function ($query) use ($id) {
                is_numeric($id) ? $query->whereId($id) : $query->whereSlug($id);
            })
            ->firstOrFail();

        //type is video redirect
        if ($article->video_id) {
            return redirect('/video/' . $article->video_id);
        }

        //draft article logic ....
        if ($article->status < 1) {
            if (!canEdit($article)) {
                return abort(404);
            }
        }

        if ($article->category && $article->category->parent_id) {
            $data['parent_category'] = $article->category->parent()->first();
        }

        //记录用户浏览记录
        $article->recordBrowserHistory();

        //parse video and image, etc...
        //$article->body = $article->parsedBody();

        $data['recommended'] = Article::whereIn('category_id', $article->categories->pluck('id'))
            ->where('id', '<>', $article->id)
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('article.show')
            ->withArticle($article)
            ->withData($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //不是编辑或者admin无法使用编辑面板
        if (!checkEditor()) {
            abort(404);
        }

        $article = Article::with('images')->findOrFail($id);
        $article->saveRelatedImagesFromBody();
        $article->load('images');

        $categories    = request()->user()->adminCategories;
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

        if ($slug = $request->slug) {
            $validator = Validator::make(
                $request->input(),
                ['slug' => 'unique:articles,slug,' . $article->id]//校验时忽略当前文章
            );
            if ($validator->fails()) {
                dd('当前slug已被使用');
            }
            if (is_numeric($slug)) {
                dd('slug 不能为纯数字');
            }
        }

        $article->update($request->all());
        $article->edited_at   = \Carbon\Carbon::now();
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->source_url  = null; //手动编辑过的文章，都不再是爬虫文章
        $article->save();

        //改变动态
        $article->changeAction();

        //保存外部图片
        $article->saveExternalImage();
        //images
        $article->saveRelatedImagesFromBody();

        //允许编辑时定时发布
        $this->process_delay($article);

        //categories
        $article->saveCategories(request('categories'));

        //tags
        $this->save_article_tags($article);

        if (!empty($article->slug)) {
            return redirect()->to('/article/' . $article->slug);
        }
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
        if (request('restore')) {
            $article->update(['status' => 1]);
        } else {
            $article->update(['status' => -1]);
        }
        //改变动态
        $article->changeAction();
        return redirect()->back();
    }

    public function process_delay($article)
    {
        if (request()->delay) {
            $article->user_id    = Auth::id();
            $article->status     = 0; //草稿
            $article->delay_time = now()->addDays(request()->delay);
            $article->save();

            DelayArticle::dispatch($article)
                ->delay(now()->addDays(request()->delay));
        }
    }

    public function save_article_tags($article)
    {
        $tag_ids  = [];
        $keywords = preg_split("/(#|:|,|，|\s)/", $article->keywords);
        foreach ($keywords as $word) {
            $word = trim($word);
            if (!empty($word)) {
                $tag = Tag::firstOrNew([
                    'name' => $word,
                ]);
                $tag->user_id = Auth::user()->id;
                $tag->save();
                $tag_ids[] = $tag->id;
            }
        }
        $article->tags()->sync($tag_ids);
    }


    public function shareVideo($id){
        $article = Article::findOrFail($id);

        return view('share.shareVideo',[
            'article' => $article,
            'video' => $article->video,
            'user' => $article->user,
        ]);
    }
}
