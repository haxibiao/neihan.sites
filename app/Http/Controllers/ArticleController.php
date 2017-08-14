<?php

namespace App\Http\Controllers;

use App\Article;
use App\ArticleImage;
use App\ArticleTag;
use App\ArticleVideo;
use App\Http\Requests\ArticleRequest;
use App\Image;
use App\Tag;
use App\Video;
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
        $article->date    = \Carbon\Carbon::now()->toDateString();
        $article->save();

        //videos
        $this->save_article_videos($request, $article);

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
        $this->auto_upadte_image_relations($imgs, $article);

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
        $article->body = str_replace("\n", '<br/>', $article->body);
        $article->body = str_replace(' style=""', '', $article->body);
        $article->body = str_replace('class="box-related-full"', "", $article->body);
        $article->body = str_replace('class="box-related-half"', "", $article->body);

        $article->body = parse_video($article->body);

        $data['json_lists'] = $this->get_json_lists($article);
        $data['related']    = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

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
        if ($article->images->isEmpty()) {
            //fix img relation missing
            $pattern_img = '/<img src=\"(.*?)\"/';
            if (preg_match_all($pattern_img, $article->body, $matches)) {
                $imgs = $matches[1];
                $this->auto_upadte_image_relations($imgs, $article);
                $article = Article::with('images')->findOrFail($id);
            }
        } else {
            //编辑文章的时候,可能没有插入图片,字段可能空,就会删除图片地址....
            if ($this->clear_article_imgs($article)) {
                $article = Article::with('images')->findOrFail($id);
            }
        }
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

        $article->update($request->except('image_url'));

        if ($request->get('primary_image')) {
            $article->image_url = $request->get('primary_image');
        } else {
            if ($request->get('image_url')) {
                $article->image_url = $request->get('image_url');
            }
        }

        $article->has_pic   = !empty($article->image_url);
        $article->edited_at = \Carbon\Carbon::now();
        $article->save();

        //videos
        $this->save_article_videos($request, $article);

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
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
        $article = Article::find($id);
        if ($article) {
            $article->delete();
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
                $article_video = ArticleVideo::firstOrNew([
                    'article_id' => $article->id,
                    'video_id'   => $video_id,
                ]);
                $article_video->save();
            }
        }
    }

    public function clear_article_imgs($article)
    {
        $article_with_images = Article::with('images')->find($article->id);
        $images              = $article_with_images->images;

        // remove unused images relationship ...
        $cleared_somthing = false;
        $pattern_img      = '/<img(.*?)>/';
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

                        $cleared_somthing = true;
                    }
                }
            }
        }
        return $cleared_somthing;
    }

    public function auto_upadte_image_relations($imgs, $article)
    {
        if (!is_array($imgs)) {
            return;
        }
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

                //auto get is_top an image_top
                if ($image->path_top) {
                    $article->is_top    = 1;
                    $article->image_top = $image->path_top;
                    $article->save();
                }

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
}
