<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Image;
use App\Jobs\ArticleDelay;
use App\Tag;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
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
        $this->article_is_top($request,$article);

        if ($request->is_Delay > 0) {
            $article->status = -1;
            $article->save();

            ArticleDelay::dispatch($article->id)
                ->delay(now()->addMinutes(60 * $request->is_Delay));
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
        $article = Article::with('user')->with('category')->with('tags')->with('images')->findOrFail($id);
        if ($article->category->parent_id) {
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

        //tags
        $this->save_article_tags($article);

         //is_top
        $this->article_is_top($request,$article);

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

    public function save_article_videos($request, $article)
    {
        $videos = $request->get('videos');
        if (is_array($videos)) {
            foreach ($videos as $video_id) {
                if (!is_numeric($video_id)) {
                    continue;
                }
                $video = Video::find($video_id);
                if ($video) {
                    $video->count = $video->count + 1;
                    $video->title = $article->title;
                    $video->save();

                }
                // $article->videos()->attach($video);
            }
        }
        $article->videos()->sync($videos);
    }

    public function fix_body($body)
    {
        $body = str_replace("\n", '<br/>', $body);
        $body = str_replace(' style=""', '', $body);
        $body = str_replace('class="box-related-full"', "", $body);
        $body = str_replace('class="box-related-half"', "", $body);
        return $body;
    }

    public function clear_article_imgs($article)
    {
        $pattern_img = '/<img(.*?)>/';
        preg_match_all($pattern_img, $article->body, $matches);
        if (!empty($matches)) {
            foreach ($article->images as $image) {
                $item_exist_in_body = false;
                foreach ($matches[0] as $img_tag) {
                    if (str_contains($img_tag, $image->path)) {
                        $item_exist_in_body = true;
                    }
                }
                if (!$item_exist_in_body) {
                    $article->images()->detach($image);
                    $image->count = $image->count - 1;
                    $image->save();
                }
            }
        }
    }

    public function auto_upadte_image_relations($imgs, $article)
    {
        if (!is_array($imgs)) {
            return;
        }

        $has_primary_top = false;
        $img_ids         = [];
        foreach ($imgs as $img) {
            $path      = parse_url($img)['path'];
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $path      = str_replace('.small.' . $extension, '', $path);
            if (str_contains($img, 'base64') || str_contains($path, 'storage/video')) {
                continue;
            }
            $image = Image::firstOrNew([
                'path' => $path,
            ]);
            if ($image->id) {
                $image->count = $image->count + 1;
                $image->title = $article->title;
                $image->save();

                $img_ids[] = $image->id;
                //auto get is_top an image_top
                if ($image->path_top) {
                    // $article->is_top    = 1;
                    if (!$has_primary_top) {
                        if ($image->path_small == $article->image_url) {
                            $has_primary_top = true;
                        }
                        $article->image_top = $image->path_top;
                        $article->save();
                    }
                }
                // $article->images()->attach($image);
            }
        }
        $article->images()->sync($img_ids);
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
                                'image_url' => get_img($article->image_url),
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
    //这里取出了全部的article模型返回到视图
    public function article_new()
    {
        $articles = Article::orderBy('id', 'desc')->paginate(10);
        return view('article.parts.article_new')->withArticles($articles);
    }

    public function article_is_top($request,$article)
    { 
       if ($request->is_top) {
            $images = Image::where('path', $article->image_url)->orWhere('path_small', $article->image_url)->get();
            $is_top=0;
            foreach($images as $image){
                    if ($image->width < 760) {
                        continue;
                    }else{
                        $is_top =1;
                    }
            }
            if($is_top==0){
                dd("上传图片太小不能上首页!");
            }
            $article->save();
        }
    }

    public function get_image_urls_from_body($body)
    {
        $images           = [];
        $pattern_img_path = '/src=\"(\/storage\/img\/\d+\.(jpg|gif|png|jpeg))\"/';
        if (preg_match_all($pattern_img_path, $body, $match)) {
            $images = $match[1];
        }
        return $images;
    }
}
