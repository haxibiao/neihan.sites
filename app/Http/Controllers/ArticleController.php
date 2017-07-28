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
        $this->save_article_imgs($imgs, $article);

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
        $article       = Article::with('user')->with('category')->with('tags')->with('images')->findOrFail($id);
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
        $article->body = str_replace("\n", '<br/>', $article->body);

        //TODO:: 现在要吧文章里的插入的视频图片,变成视频来播放 [视频的尺寸还是不完美，后面要获取到视频的尺寸才好处理, 先默认用半个页面来站住]
        $pattern_img_video = '/<img src=\"([^"]*?)\" data-video\=\"(\d+)\" ([^>]*?)>/iu';
        if (preg_match_all($pattern_img_video, $article->body, $matches)) {
            foreach ($matches[2] as $i => $match) {
                $img_html = $matches[0][$i];
                $video_id = $match;

                $video = Video::find($video_id);
                if ($video) {
                    $video_html    = '<div class="row"><div class="col-md-6"><div class="embed-responsive embed-responsive-4by3"><video class="embed-responsive-item" controls poster="' . $video->cover . '"><source src="' . $video->path . '" type="video/mp4"></video></div></div></div>';
                    $article->body = str_replace($img_html, $video_html, $article->body);
                }
            }
        }

        $related_articles = Article::where('category_id', $article->category_id)
            ->where('id', '<>', $article->id)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        return view('article.show')->withArticle($article)->withRelatedArticles($related_articles);
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
                $this->save_article_imgs($imgs, $article);
                $article = Article::with('images')->findOrFail($id);
            }
        } else {
            if ($this->clear_article_imgs($article)) {
                $article = Article::with('images')->findOrFail($id);
            }
        }
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

        $article->has_pic   = !empty($article->image_url);
        $article->edited_at = \Carbon\Carbon::now();
        $article->save();

        //videos
        $this->save_article_videos($request, $article);

        //tags
        $this->save_article_tags($article);

        //images
        $imgs = $request->get('images');
        $this->save_article_imgs($imgs, $article);

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

    public function save_article_imgs($imgs, $article)
    {
        if (!is_array($imgs)) {
            return;
        }
        foreach ($imgs as $img) {
            $path      = parse_url($img)['path'];
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $path      = str_replace('.small.' . $extension, '', $path);
            $image     = Image::firstOrNew([
                'path' => $path,
            ]);
            $image->path_small = $path . '.small.' . $extension;
            $image->count      = $image->count + 1;
            $image->title      = $article->title;
            $image->save();

            $article_image = ArticleImage::firstOrNew([
                'article_id' => $article->id,
                'image_id'   => $image->id,
            ]);

            //auto get is_top an image_top
            if ($image->path_top) {
                $article->is_top    = 1;
                $article->image_top = $image->path_top;
                $article->save();
            }
            $article_image->save();
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
}
