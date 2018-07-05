<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Image;
use App\Jobs\DelayArticle;
use App\Tag;
use App\Video;
use Auth;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
        $this->middleware('auth.editor')->except('index','show');
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
        $query = Article::orderBy('id', 'desc')
            ->where('status', '>', 0)
            ->whereType('article');
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
        $this->process_category($article);

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

        //draft article logic ....
        if ($article->status <= 0) {
            $has_permission_view_draf = false;
            if (checkEditor() || $article->isSelf()) {
                $has_permission_view_draf = true;
            }
            if (!$has_permission_view_draf) {
                return abort(404);
            }
        }

        if ($article->category && $article->category->parent_id) {
            $data['parent_category'] = $article->category->parent()->first();
        }

        //记录用户浏览记录
        $article->recordBrowserHistory();
        
        $article->timestamps = false;
        $article->save();
        $article->timestamps = true;

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
        $this->process_category($article);

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
        $article->update(['status' => -1]);

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

    public function get_json_lists($article)
    {
        $lists = json_decode($article->json, true);
        // return $lists;
        $lists_new = [];
        if (is_array($lists)) {
            foreach ($lists as $key => $data) {
                if (!is_array($data)) {
                    $data = [];
                }
                $items = [];
                if (!empty($data['aids']) && is_array($data['aids'])) {
                    foreach ($data['aids'] as $aid) {
                        $article = Article::find($aid);
                        if ($article) {
                            $items[] = [
                                'id'        => $article->id,
                                'title'     => $article->title,
                                'image_url' => $article->image_url,
                            ];
                        }
                    }
                }
                if (!empty($items)) {
                    $data['items']   = $items;
                    $lists_new[$key] = $data;
                }
            }
        }
        return $lists_new;
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

    public function process_category($article)
    {
        $old_categories   = $article->categories;
        $new_categories   = json_decode(request('categories'));
        $new_category_ids = [];
        //记录选得第一个做文章的主分类，投稿的话，记最后一个专题做主专题
        if (!empty($new_categories)) {
            $article->category_id = $new_categories[0]->id;
            $article->save();

            foreach ($new_categories as $cate) {
                $new_category_ids[] = $cate->id;
            }
        }
        //sync
        $parameters = [];
        foreach ($new_category_ids as $category_id) {
            $parameters[$category_id] = [
                'submit' => '已收录',
            ];
        }
        $article->categories()->sync($parameters);  
        // $article->categories()->sync($new_category_ids);

        //re-count
        if (is_array($new_categories)) {
            foreach ($new_categories as $category) {
                //更新新分类文章数
                if ($category = Category::find($category->id)) {
                    $category->count = $category->publishedArticles()->count();
                    $category->save();
                }
            }
        }
        foreach ($old_categories as $category) {
            //更新旧分类文章数
            $category->count = $category->publishedArticles()->count();
            $category->save();
        }
    }
}
