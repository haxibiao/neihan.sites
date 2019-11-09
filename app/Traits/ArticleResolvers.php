<?php

namespace App\Traits;

use App\Article;
use App\Category;
use App\Config;
use App\Contribute;
use App\Exceptions\GQLException;
use App\Exceptions\UserException;
use App\Gold;
use App\Notifications\ReceiveAward;
use App\User;
use App\Video;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait ArticleResolvers
{
    public function resolveTrashArticles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = getUser();
        return $user->removedArticles();
    }

    public function restoreArticle($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $article = Article::findOrFail($args['id']);
        $article->update(['status' => 0]);
        $article->changeAction();

        return $article;
    }

    public function deleteArticle($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $article = Article::findOrFail($args['id']);
        $article->forceDelete();

        return $article;
    }

    public function resolvePendingArticles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user       = getUser();
        $articles   = [];
        $categories = isset($args['category_id']) ? [\App\Category::find($args['category_id'])] : $user->adminCategories;

        foreach ($categories as $category) {
            $result = $category->newRequestArticles()->get();
            foreach ($result as $article) {
                $articles[] = $article;
            }
        }
        return $articles;
    }

    public function resolveRecommendArticles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //FIXME: 日后真的按当前登录用户改进推荐算法...
        $qb = \App\Article::whereStatus(1)
            ->whereNotNull('title')
            ->whereNotNull('cover_path');
        return $qb->latest('id');
    }

    public function resolveFollowedArticles($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        //TODO: 关注的文集，人的文章还没加入...
        $user     = \App\User::findOrFail($args['user_id']);
        $cate_ids = $user->followingCategories()->pluck('followed_id');
        $qb       = self::whereIn('category_id', $cate_ids);
        return $qb;
    }

    public function resolveCreatePost($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $article = new \App\Article();
        $article->createPost($args);

        //直接关联到专题
        if (!empty($args['category_ids'])) {
            //排除重复专题
            $category_ids = array_unique($args['category_ids']);
            $category_id  = reset($category_ids);
            array_shift($category_ids);

            //第一个专题为主专题
            $article->category_id = $category_id;
            $article->save();

            if ($category_ids) {
                $article->categories()->sync($category_ids);
            }
        }

        return $article;
    }

    // public function resolveRecommendVideos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     $user = checkUser();

    //     //已经与前端沟通避开，避开page变动

    //     //随机在50个最新更新的专题中取(需要内容数量够10+)
    //     $randCateId = \App\Category::where('count', '>', 10)->select('id')->orderByDesc('updated_at')->take(50)->toArray();

    //     $cate_key = 'visited_max_article_id_for_' . $user->id;

    //     if (!is_null($user)) {
    //         $maxArticleId = cache()->store('database')->get($cate_key, 0);
    //         $builder      = Article::orderBy('id', 'desc')->whereStatus(1)->whereIn('category_id', $randCateId)
    //             ->whereType('video')
    //             ->whereNotIn('video_id', function ($query) use ($user) {
    //                 $query->select('visited_id')
    //                     ->from('visits')
    //                     ->where('user_id', $user->id)
    //                     ->where('visited_type', 'videos');
    //             });

    //         //简单视频去重(与奖励接口配套使用),对前端不可见
    //         if ($maxArticleId != 0) {
    //             $builder = $builder->where('id', '<', $maxArticleId);
    //         }
    //         $results = $builder->paginate($args['count'], $columns = ['*'], $pageName = 'page', 1);

    //         //循环队列
    //         $pageLastItem = Arr::last($results->items());
    //         if ($pageLastItem) {
    //             cache()->store('database')->forever($cate_key, $pageLastItem->id);
    //         }

    //         if (($results->lastPage() - 1) == $results->currentPage()) {
    //             //获取最大的文章ID
    //             $maxArticleId = Article::max('id');
    //             cache()->store('database')->forever('visited_max_article_id_for_' . $user->id, $maxArticleId);
    //         }

    //         //TODO 边界问题：视频不够数列表为空

    //     } else {
    //         //TODO 记录访客的浏览记录，避免重新唤起App观看相同视频影响用户体验
    //         $results = Article::whereStatus(1)
    //             ->whereType('video')
    //             ->orderBy('id', 'desc')
    //             ->paginate($args['count'], $columns = ['*'], $pageName = 'page', $args['page']);
    //     }

    //     //简单实现广告位
    //     //TODO 边界问题：边界记录不足5条
    //     for ($i = 0; $i < $results->count(); $i++) {
    //         if (($i + 1) % 5 == 0) {
    //             $article               = $results->get($i);
    //             $article->isAdPosition = true;
    //         }
    //     }
    //     return $results;
    // }

    public function resolveRecommendVideos($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $pageCount              = $args['count'];
        $targetLatestCatesCount = 50; //50个 (目前才100多分类，后面的就是不够新的内容了)

        // 登录状态
        if ($user = checkUser()) {

            //缓存key
            $cache_key = "user_{$user->id}_done_cate_ids";

            $visited_ids = $user->visitedVideos()->select('visited_id')->get()->toArray();
            $builder     = Article::whereStatus(1)
                ->whereNotNull('video_id'); //只要有视频的，都刷，包含问答

            // filter:排除 (用户看过) 的视频
            $builder = $builder->whereNotIn('video_id', $visited_ids);

            // filter: 这个用户刷完了热门视频
            if (\Cache::has($cache_key)) {
                // 排除用户刚看完的分类
                $done_cate_ids = \Cache::get($cache_key);
                $builder       = $builder->whereNotIn('category_id', $done_cate_ids);
            } else {
                // 选择最近更新的分类
                $category_ids = Category::orderBy('updated_at', 'desc')->select('id')->take($targetLatestCatesCount)->get()->toArray();
                $builder      = $builder->whereIn('category_id', $category_ids);
                // 已经刷完最新的这些分类
                if ($builder->count() < $pageCount) {
                    // 标记1天排除
                    \Cache::put($cache_key, $category_ids, now()->addDay(1));
                }
            }

            //order: 随机
            $builder = $builder->inRandomOrder();
            //get: pageCount
            $results = $builder->paginate($pageCount, $columns = ['*'], $pageName = 'page', 1);

            //简单实现浏览记录：当页视频记录为已浏览过 //TODO: 后面需要前端提交真实播放时间回来记录
            \App\Visit::saveListedVideos($user, $results);

        } else {
            $results = Article::whereStatus(1)
                ->whereNotNull('video_id')
            // ->whereType('video')
                ->inRandomOrder()
                ->paginate($pageCount, $columns = ['*'], $pageName = 'page', $args['page']);
        }

        //简单实现广告位
        //TODO 边界问题：边界记录不足5条
        for ($i = 0; $i < $results->count(); $i++) {
            if (($i + 1) % 5 == 0) {
                $article               = $results->get($i);
                $article->isAdPosition = true;
            }
        }
        return $results;
    }

    /**
     * 根据 抖音上的分享链接，爬取信息，转存到我们库中
     *
     * @return void 返回 article对象 时，没有cover 和 video info，需要等待队列执行完成后，信息才会更新
     * @author zengdawei
     */
    public function resolveDouyinVideo($rootValue, array $args, $context, $resolveInfo)
    {
        $user = getUser();
        try{
            // 过滤文本，留下 url
            $shareMsg = $args['share_link'];
            $link = filterText($shareMsg)[0];

            // 不允许重复视频
            if (Article::where('source_url', $link)->exists()) {
                throw new GQLException('视频已经存在，请换一个视频噢');
            }
            //轮训爬虫服务器
            $prossserServicer = [
                'gz01.haxibiao.com',
                'gz02.haxibiao.com',
                'gz03.haxibiao.com',
                'gz04.haxibiao.com',
                'gz05.haxibiao.com',
                'gz06.haxibiao.com',
                'gz07.haxibiao.com',
                'gz08.haxibiao.com',
            ];

            $endPoint = Arr::random($prossserServicer);

            //轮训Job
            $getApi = 'http://' . $endPoint . '/simple-spider/index.php?url=' . $link ;
            $data = file_get_contents($getApi);
            $json   = json_decode($data, true);


            $content = $json['data'];
            if($json['code'] != 200){
                //TODO 记录重试机制
                throw new GQLException('视频上传失败!');
            }
            //保存视频信息
            $url= $content['video'];
            $raw= $content['raw'];

            $description = Str::replaceFirst('#在抖音，记录美好生活#','',$shareMsg);
            if(Str::contains($description,'#')){
                $description = Str::before($description,'#');
            } else {
                $description = Str::before($description,'http');
            }

            $dispatcher = Video::getEventDispatcher();
            Video::unsetEventDispatcher();

            $hash  = md5_file($url);
            $video = Video::firstOrNew([
                'hash' => $hash,
            ]);
            $video->setJsonData('metaInfo', $raw);
            $video->setJsonData('server'  , $url);
            $video->user_id = $user->id;
            $video->title = $description;
            $video->unsetEventDispatcher();//临时禁用模型事件
            $video->save();

            //本地存一份用于截图
            $cosPath     = 'video/' . $video->id . '.mp4';
            $video->path  = $cosPath;
            Storage::disk('public')->put($cosPath, @file_get_contents($url));
            $video->disk = 'local'; //先标记为成功保存到本地
            $video->save();

            Video::setEventDispatcher($dispatcher);

            //TODO 抖音爬虫不用走视屏处理逻辑了,抖音接口信息很完整
            //将视频上传到cos
            $cosDisk = Storage::cloud();
            $cosDisk->put($cosPath, Storage::disk('public')->get($cosPath));
            $video->disk = 'cos';
            $video->save();

            $category = Category::firstOrNew([
                'name' => '抖音合集'
            ]);
            if(!$category->id){
                $category->name_en  = 'douyin';
                $category->status   = 1;
                $category->user_id  = 1;
                $category->save();
            }
            //避免与Observer处理存在时间差导致重复创建
            $article = \App\Article::firstOrNew([
                'video_id' => $video->id,
            ]);
            //FIXME 重构描述抖音信息，直接通过接口获取视频信息
            $article->body        = $description;
            $article->title       = Str::limit($description, 150); //截取微博那么长的内容存简介;
            $article->description = Str::limit($description, 280); //截取微博那么长的内容存简介
            $article->source_url  = $link;
            $article->status      = 1;//FIXME 合并submit与status字段
            $article->submit      = Article::SUBMITTED_SUBMIT; //待审核状态
            $article->type        = 'post';
            $article->user_id     = $user->id;
            $article->category_id = $category->id;
            $article->save();

            //奖励用户
            $user->notify(new ReceiveAward('发布视频动态奖励', 10, $user, $article->id));
            Gold::makeIncome($user, 10, '发布视频动态奖励');
            Contribute::rewardUserVideoPost($user,$article);

            return $article;
        } catch (\Exception $e){

            if ($e->getCode() == 0) {
                Log::error($e->getMessage());
                throw new GQLException('视频上传失败!');
            }
            throw new GQLException($e->getMessage());
        }
    }

}
