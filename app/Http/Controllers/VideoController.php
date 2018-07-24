<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Requests\VideoRequest;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //取置顶视频专题
        // $stick_video_categories = get_stick_video_categories();
        // $video_category = [];
        // foreach ($stick_video_categories as $video_categories) {
        //     $video_id = $video_categories->id;
        //     $video_category[$video_categories->name]['video'] = Article::find($video_id)->orderby('count_likes','desc')->paginate(3);
        // }

        //热门专题，简单规则就按视频数多少来判断专题是否热门视频专题
        $categories = Category::orderBy('count_videos', 'desc')->take(3)->get();
        $data       = [];
        foreach ($categories as $category) {
            $articles = $category->hasManyVideoArticles()
                ->where('status', '>', 0)
                ->orderByDesc('hits')
                ->take(3)
                ->get();
            if (!$articles->isEmpty()) {
                $data[$category->name] = $articles;
                foreach ($articles as $article) {
                    $article->image_url = $article->primaryImage();
                }
            }
        }
        return view('video.index')->with('data', $data);
    }

    function list(Request $request) {
        $videos = Video::with('user')->with('article.category')->orderBy('id', 'desc')->where('status', '>=', 0);
        //Search videos
        if ($request->get('q')) {
            $keywords = $request->get('q');
            $videos   = Video::with('user')->with('article.category')->orderBy('id', 'desc')->where('status', '>=', 0)
                ->where('title', 'like', "%$keywords%");
        }
        $videos = $videos->paginate(10);
        return view('video.list')
            ->withVideos($videos); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['video_categories'] = Category::pluck('name', 'id');
        return view('video.create')->withData($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoRequest $request)
    {
        ini_set('memory_limit', '256M');
        $uploadSuccess = false;
        //如果是通过表单上传文件
        $file = $request->file('video');
        if ($file) {
            $hash  = md5_file($file->path());
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            if ($video->id) {
                abort(500, "相同视频已存在");
            }

            $title       = $request->title;
            $description = $request->description;
            if (empty($title)) {
                $title = str_limit($description, $limit = 20, $end = '...');
            }
            $video->title = $title;

            $video->save();

            //save article
            $article             = new Article();
            $article->user_id    = getUserId();
            $params['video_url'] = $video->getPath();
            $params['type']      = 'video';
            $params['image_url'] = '/images/uploadImage.jpg'; //默认图
            $params['video_id']  = $video->id;
            $article->fill($params);
            //文章title
            $article->title       = $title;
            $article->description = $description;
            $article->save();

            //处理视频与分类的关系
            $this->process_category($article);

            $uploadSuccess = $video->saveFile($file);
        }
        if (!$uploadSuccess) {
            //视频上传失败
            abort(500, '视频上传失败');
        }
        return redirect()->to('/video');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::with('article')
            ->with('user')
            ->findOrFail($id);
        $article = $video->article;
        if (empty($article) || $article->status < 0) {
            abort(404);
        }
        //最后一个分类
        $categories = $article->categories()
            ->whereHas('videoArticles', function ($query) {
                $query->where('count_videos', '>', 0);
            })->get();
        $category = $categories->last();
           
        //记录用户浏览记录
        $article->recordBrowserHistory();
        //获取关联视频
        $data['related'] = $this->getRelationVideo($article, 4);
        
        return view('video.show')
            ->withVideo($video) 
            ->withData($data)
            ->withCategory($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video                    = Video::findOrFail($id);
        $data['video_categories'] = Category::pluck('name', 'id');
        //获取视频截图图片地址,相对地址
        $covers  = [];
        $img_url = $video->article->image_url;
        for ($i = 1; $i <= 8; $i++) {
            $cover = $img_url . "." . $i . ".jpg";
            if (file_exists(public_path($cover))) {
                $covers[] = $cover;
            }
        }
        $data['thumbnails'] = $covers;

        return view('video.edit')
            ->withVideo($video)
            ->withData($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VideoRequest $request, $id)
    {
        $video   = Video::findOrFail($id);
        $article = $video->article;
        //维护分类关系
        $this->process_category($article);
        //选取封面图
        if (!empty($request->thumbnail)) {
            $result = copy(
                public_path($request->thumbnail),
                public_path($article->image_url)
            );
            $article->status = 1;
            $article->save();
        }

        //save article description ...
        $article->update($request->all());

        //文件发生变动 TODO:注意这里没有删除磁盘上的文件，后面的兄弟注意一下
        //这里不能使用直接使用$request->video。因为与路由参数重名了
        $file           = $request->file('video');
        $file_is_modify = !empty($file)
        &&
        md5_file($file->path()) != $video->hash;

        if ($file_is_modify) {
            //视频源发生变动时首页暂时隐藏，因为有截图延迟
            $video->update(['status' => 0]);
            $article->update(['status' => 0]);
            if (!$video->saveFile($request->video)) {
                //视频上传失败
                abort(500, '视频上传失败');
            }
        }

        //防止用户直接访问编辑界面无session导致页面报错
        $refer_url = session('url.intended');
        if (empty($refer_url)) {
            return redirect()->to('/video');
        }
        return redirect($refer_url);
    }

    /**
     * 删除视频是软删除，同时删除磁盘上的视频文件
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video   = Video::findOrFail($id); 
        $article = $video->article;

        //软删除 video
        $video->status = -1;
        $video->save();
        //软删除 article
        $article->update(['status' => -1]); 

        //维护分类关系
        $this->recountCategory($article);  

        //TODO 清除关系 分类关系 冗余的统计信息  评论信息 点赞信息 喜欢的信息 收藏的信息
        return redirect()->to('/video/list');
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- 算法策略 ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @Desc     获取相关视频
     * @DateTime 2018-07-24
     * @return   [type]     [description]
     */
    public function getRelationVideo($article, $need_length){
        //关联视频
        $related_group = null;
        $article_ids = [$article->id];
        //获取有视频的分类
        $categories = $article->categories()
            ->whereHas('videoArticles', function ($query) {
                $query->where('count_videos', '>', 0);
            })->get();
        
        if ( $categories->isNotEmpty() ) {
            //优先从最后一个分类中随机选择4个 
            $category = $categories->pop();
            $related_group = $category
                ->videoArticles()
                ->where('articles.status', 1)
                ->where('articles.id','<>',$article->id)
                ->take($need_length)
                ->get();
            if ( $related_group->isNotEmpty() ) {
                $article_ids = array_merge(
                    $related_group->pluck('id')->toArray(), $article_ids
                );
                $related_group = $related_group;
            }
        }
        //视频数据不够时，还填充的视频数
        $fill_length   = $need_length - count($related_group);
        //主专题下文章数不够时候随机填充至4个()
        if( $fill_length > 0 ){
            //暂时不实现复杂的策略,减少系统Query
            /*$category_ids   = $categories->pluck('id');
            $ids = \DB::table('article_category')
                ->whereIn('category_id',$category_ids)
                ->where('已收录')
                ->whereNotIn('article_id',$article_ids)
                ->pluck('article_id');
            if( count($ids)>0 ){
                $articles = Article::whereIn('id', $ids
                    ->take($fill_length));

                $data['related'] = $data['related']
                    ->merge($articles);
            }*/

            $related = Article::where('type', 'video')
                ->orderBy('id', 'desc')
                ->whereStatus(1)
                ->whereNotIn('id',$article_ids)
                ->orderBy(\DB::raw('RAND()'))
                ->take(100)   //取最近上传的100个视频随机
                ->get()
                ->random($fill_length);
            $related_group = $related_group->merge( $related );
        }
        return $related_group;
    }
     /**
     * @Desc     删除视频重新计算视频与分类的关系
     * @DateTime 2018-06-27
     * @param    [type]     $article article是一篇type为video的文章
     * @return   [type]            [description]
     */
    public function recountCategory($article)
    {
            //更新article表上冗余的主分类
            $article->category_id = null;
            $article->save(['timestamps'=>false]);
            
            //删除分类关系
            $categories   = $article->categories;
            $article->categories()->detach();
            
            //更新旧分类视频数
            foreach ($categories as $category) {
                $category->count_videos = $category
                    ->videoArticles()
                    ->count();
                $category->save();
            }
    }
}
