<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Movie;
use App\User;
use Auth;

class IndexController extends Controller
{
    /**
     * 首页数据的全部逻辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // User::find(2)->notify((new CommentAccepted(Comment::find(1),User::find(1)))->onQueue('aaaa'));
        // dd(User::find(2)->notifications()->first()->markAsRead());
        if (isRecording()) {
            return view('app');
        }

        $data = (object) [];

        // $sticks = Stick::where('name', '首页轮播图')->take(5)->get();
        // if (count($sticks) < 5) {
        //首页轮播图(暂时糊弄5个电影，引导去电影频道)
        $data->carousel = Movie::latest('id')->take(5)->get();
        // } else {
        // $data->carousel = Stick::items($sticks);
        // }

        //首页专题
        $data->categories = $this->indexTopCategories();

        //首页推荐视频
        // $sticks = Stick::where('name', '首页短视频')->take(4)->get();
        // if (count($sticks) < 4) {
        //首页轮播图(暂时糊弄5个电影，引导去电影频道)
        // $data->videoPosts = Article::with('video')
        //     ->where('type', 'video')
        //     ->orderByDesc('id')
        //     ->where('status', '>', 0)
        //     ->take(4)->get();
        $data->videoPosts = \App\Post::has('collections')
            ->with('video')
            ->where('status', '>', 0)
            ->orderByDesc('id')
            ->take(4)
            ->get();
        // } else {
        //     $data->videoPosts = Stick::items($sticks);
        // }

        //首页文章
        $data->articles = indexArticles();

        return view('index.index')
            ->withData($data);
    }

    /**
     * 首页的专题
     * @return [category] [前几个专题的数组]
     */
    private function indexTopCategories()
    {
        //首页推荐分类
        $stick_categories    = get_stick_categories();
        $stick_categorie_ids = array_pluck($stick_categories, 'id');
        $top_count           = 7 - count($stick_categories);

        //已登录,专题的获取顺序为：置顶的专题>关注的专题>官方大专题
        if (Auth::check()) {
            $user = Auth::user();

            //获取所有关注的专题
            $all_follow_category_ids = \DB::table('follows')->where('user_id', $user->id)
                ->where('followed_type', 'categories')
                ->whereNotIn('follows.followed_id', $stick_categorie_ids)
                ->whereExists(function ($query) {
                    return $query->from('categories')
                        ->whereRaw('categories.id = follows.followed_id')
                        ->where('categories.status', '>=', 0)
                        ->where('categories.is_official',0);
                })->take($top_count)->pluck('followed_id')->toArray();
            $category_ids = array_merge($stick_categorie_ids, $all_follow_category_ids);

            //置顶专题加上关注的专题都不够7个时获取官方大专题
            if (count($category_ids) != 7) {
                $official_category_ids = Category::where('is_official', 0)
                    ->where('count', '>=', 0)
                    ->where('status', '>=', 0)
                    ->where('parent_id', 0) //0代表顶级分类
                    ->whereNotIn('id', $category_ids)
                    ->take(7 - count($category_ids))
                    ->pluck('id')->toArray();
                $category_ids = array_merge($category_ids, $official_category_ids);
            }
            $categories = Category::whereIn('id', $category_ids)->get();
        } else {

            //未登录，随机取官方专题
            $categories = Category::where('is_official', 0)
                ->where('count', '>=', 0)
                ->where('status', '>=', 0)
                ->where('parent_id', 0) //0代表顶级分类
                ->whereNotIn('id', $stick_categorie_ids)
                ->orderBy(\DB::raw('RAND()'))
                ->take($top_count)
                ->get();
        }

        //首页推荐专题 合并置顶的专题
        $categories = get_top_categoires($categories);
        return $categories;
    }

    public function app()
    {
        app_track_event('网页', 'App下载');

        return view('app');
    }

    public function aboutUs()
    {
        return view('index.about_us');
    }

    public function trending()
    {
        if (request('type') == 'thirty') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereIn('type',['diagrams','articles','article'])
                ->where('status', '>', 0)
                ->where('updated_at', '>', \Carbon\Carbon::now()->addDays(-30))
                ->paginate(10);
            if ($articles){
                $articles = Article::orderBy('hits', 'desc')
                    ->whereIn('type',['diagrams','articles','article'])
                    ->where('status', '>', 0)
                    ->paginate(10);
            }
        } else if (request('type') == 'seven') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereIn('type',['diagrams','articles','article'])
                ->where('status', '>', 0)
                ->where('updated_at', '>', \Carbon\Carbon::now()->addDays(-7))
                ->paginate(10);
            if ($articles){
                $articles = Article::orderBy('hits', 'desc')
                    ->whereIn('type',['diagrams','articles','article'])
                    ->where('status', '>', 0)
                    ->paginate(10);
            }
        } else {
            $articles = Article::where('status', '>', 0)
                ->whereIn('type',['diagrams','articles','article'])
                ->orderBy('hits', 'desc')
                ->paginate(10);
        }

        return view('index.trending')->withArticles($articles);
    }

    public function write()
    {
        return view('write');
    }
}
