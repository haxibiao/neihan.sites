<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Jobs\DelayArticle;
use App\Tag;
use Auth;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('auth.editor')->except('index', 'show', 'storePost', 'edit','destroy'); //编辑自己的文章的时候，无需编辑身份
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
        //TODO:: 记录用户动作
        return redirect()->to($article->content_url());
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
        if ($request->get('q')) {
            $keywords = $request->get('q');
            $query    = Article::orderBy('id', 'desc')
                ->where('status', '>', 0)
                ->whereType('article')
                ->where('title', 'like', "%$keywords%");
        }
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
        $user    = $request->user();
        $article = new Article($request->all());

        $article->has_pic = !empty($article->image_url);
        $article->date    = \Carbon\Carbon::now()->toDateString();

        $article->image_url   = $this->get_primary_image();
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->save();

        //delay
        $this->process_delay($article);

        //categories
        $article->saveCategories(request('categories'));

        //tags
        $this->save_article_tags($article);

        //images
        $article->saveRelatedImagesFromBody();

        //record action
        $article->recordAction();

        $user->count_articles = $user->articles()->count();
        $user->count_words    = $user->articles()->sum('count_words');
        $user->save();

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
        $article = Article::with('user')->with('category')->with('tags')->with('images')->findOrFail($id);

        //type is video redirect
        if ($article->type == 'video') {
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
        $article->body = $article->parsedBody();

        $data['recommended'] = Article::whereIn('category_id', $article->categories->pluck('id'))
            ->where('id', '<>', $article->id)
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
        $article = Article::with('images')->findOrFail($id);

        //fix img relation missing, 同是修复image_url对应的image_top 为主要image_top
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
        $article->update($request->all());
        $article->image_url   = $this->get_primary_image();
        $article->has_pic     = !empty($article->image_url);
        $article->edited_at   = \Carbon\Carbon::now();
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);
        $article->save();

        //images
        $article->saveRelatedImagesFromBody();

        //允许编辑时定时发布
        $this->process_delay($article);

        //categories
        $article->saveCategories(request('categories'));

        //tags
        $this->save_article_tags($article);

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

    public function get_primary_image()
    {
        if (request('primary_image')) {
            $image_url = request('primary_image');
        } else {
            $image_url = request('image_url');
        }
        $image_url = parse_url($image_url, PHP_URL_PATH);
        // $image_url          = str_replace('.small', '', $image_url);
        return $image_url;
    }
}
