<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\User;
use Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class IndexController extends Controller
{
    public function index()
    {
        return $this->indexRecommendationStrategy();
    }

    public function app()
    {
        if (file_exists(resource_path('views/index/apps/' . get_domain_key() . '.blade.php'))) {
            return view('index.apps.' . get_domain_key());
        } else {
            return "app下载页面正在制作中...";
        }
    }

    public function aboutUs()
    {
        return view('index.about_us');
    }

    public function trending()
    {
        if (request('type') == 'thirty') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereType('article')
                ->where('status', '>', 0)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-30))
                ->paginate(10);
        } else if (request('type') == 'seven') {
            $articles = Article::orderBy('hits', 'desc')
                ->whereType('article')
                ->where('status', '>', 0)
                ->where('created_at', '>', \Carbon\Carbon::now()->addDays(-7))
                ->paginate(10);
        } else {
            $articles = Article::where('status', '>', 0)
                ->whereType('article')
                ->orderBy('hits', 'desc')
                ->paginate(10);
        }

        return view('index.trending')->withArticles($articles);
    }

    public function write()
    {
        return view('write');
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- service ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * 基与原来逻辑修改的首页显示逻辑
     *     TODO 下周一上线,注意修改index模版文件
     * @return [type] [description]
     */
    public function indexRecommendationStrategy()
    {
        //首页推荐分类
        $stick_categories = get_stick_categories();
        //已登录,只显示关注的专题
        if (Auth::check()) {
            $user = Auth::user();
            //获取所有关注的专题
            $all_follow_category_ids = \DB::table('follows')->where('user_id', $user->id)
                ->where('followed_type', 'categories')
                ->whereExists(function ($query) {
                    return $query->from('categories')
                        ->whereRaw('categories.id = follows.followed_id')
                        ->where('categories.status', '>=', '0');
                })->pluck('followed_id');
            $categories = Category::whereIn('id', $all_follow_category_ids)
                ->take(7)
                ->get();
            //未登录或者未关注任何专题，随机取官方专题
        } else {
            $top_count  = 7 - count($stick_categories);
            $categories = Category::where('is_official', 1)
                ->where('count', '>=', 0)
                ->where('status', '>=', 0)
                ->orderBy(\DB::raw('RAND()'))
                ->take($top_count)
                ->get();
        }
        //首页推荐专题 合并置顶的专题
        $categories       = get_top_categoires($categories);
        $data             = (object) [];
        $data->categories = $categories;

        //首页文章
        $stick_article_ids = array_column(get_stick_articles('发现'), 'id');
        $qb                = Article::with('user')
            ->with('category')
            ->exclude(['json'])
            ->whereIn('id', $stick_article_ids)
            ->unionAll(
                Article::from('articles')->with('user')->with('category')
                    ->exclude(['json'])
                    ->where('status', '>', 0)
                    ->where('source_url', '=', '0')
                    ->whereNotNull('category_id') 
                    ->whereNotIn('id', $stick_article_ids)
                    ->orderBy('updated_at', 'desc')->limit(\DB::table('articles')->max('id'))
            );
        $total    = count($qb->get());
        $articles = $qb->offset((request('page', 1) * 10) - 10)
            ->take(10)
            ->get();
        //首页异步加载
        if (request()->ajax() || request('debug')) {
            //下面是为了兼容VUE
            $articles = new LengthAwarePaginator(new Collection($articles), $total, 10);
            foreach ($articles as $article) {
                $article->fillForJs();
                $article->time_ago = $article->updatedAt();
            }
            return $articles;
        }

        //移动端，用简单的分页样式
        if (\Agent::isMobile()) {
            $data->articles = new Paginator(new Collection($articles), 10);
            $data->articles->hasMorePagesWhen($total > request('page') * 10);
        } else {
            $data->articles = new LengthAwarePaginator(new Collection($articles), $total, 10);
        }
        //dd($data->articles);
        //首页轮播图
        $data->carousel = get_top_articles();
        //首页推荐视频
        $data->videoPosts = Article::with('video')->where('type', 'video')
            ->orderBy('id', 'desc')
            ->where('status', '>', 0)
            ->paginate(4);

        return view('index.index')
            ->withData($data);
    }
    /**
     * 首页算法历史版本
     * @return [type] [description]
     */
    public function oldIndexRecommendationStrategy(){
    	$has_follow_articles = false;
        //get user related categories ..
        $categorie_ids = [];

        $stick_categories = get_stick_categories();
        $top_count        = 7 - count($stick_categories);
        if ($top_count) {
            foreach ($stick_categories as $stick_category) {
                $categorie_ids[] = $stick_category->id;
            }
        }
        //已登录，优先显示专注的专题的文章
        if (Auth::check()) {
            $user = Auth::user();
            //get top n user followed categories
            if ($user->followingCategories()->count() > 6) {
                $follows = $user->followingCategories()
                    ->orderBy('id', 'desc')
                    ->take($top_count)
                    ->get();
                $categories = [];

                foreach ($follows as $follow) {
                    $category        = $follow->followed;
                    $categories[]    = $category;
                    $categorie_ids[] = $category->id;
                }
                // get user followed categories related articles ...
                $articles = Article::with('user')->with('category')
                    ->where('status', '>', 0)
                    ->where('source_url', '=', '0')
                    ->whereIn('category_id', $categorie_ids)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);

                if (!$articles->isEmpty()) {
                    $has_follow_articles = true;
                }
            }
        }

        //未登录或者未关注任何专题，直接取官方专题和文章
        if (!$has_follow_articles) {
            $categories = Category::orderBy('is_official', 'desc')
                ->where('count', '>=', 0)
                ->where('status', '>=', 0)
                ->orderBy('updated_at', 'desc')
                ->take($top_count)
                ->get();
            $articles = Article::with('user')->with('category')
                ->where('status', '>', 0)
                ->where('source_url', '=', '0') 
                ->whereIn('category_id', array_merge($categorie_ids, $categories->pluck('id')->toArray()))
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        }

        //load more articles ...
        if (request()->ajax() || request('debug')) {
            foreach ($articles as $article) {
                $article->fillForJs();
                $article->time_ago = $article->updatedAt();
            }
            return $articles;
        }

        $categories       = get_top_categoires($categories);
        $data             = (object) [];
        $data->categories = $categories;

        //get sticks and filter sticks ....
        $total        = $articles->total();
        $sticks       = new Collection(get_stick_articles('发现'));
        $data->sticks = $sticks;
        $articles     = $articles->filter(function ($article) use ($sticks) {
            return !in_array($article->id, $sticks->pluck('id')->toArray());
        });
        if (request()->page > 1) {
            $data->sticks = [];
        }

        //移动端，用简单的分页样式
        if (\Agent::isMobile()) {
            $data->articles = new Paginator(new Collection($articles), 10);
            $data->articles->hasMorePagesWhen($total > request('page') * 10);
        } else {
            $data->articles = new LengthAwarePaginator(new Collection($articles), $total, 10);
        }

        $data->carousel = get_top_articles();

        //首页推荐视频  TODO  评论时间来排序
        $data->videoPosts = Article::with('video')->where('type', 'video')
            ->orderBy('id', 'desc')
            ->where('status', '>', 0)
            ->paginate(4);
        return view('index.index')
            ->withData($data);
    }
}
