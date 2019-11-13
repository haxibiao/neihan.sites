<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use App\Gold;
use App\Tag;
use App\Transaction;
use App\User;
use App\Video;
use App\Visit;
use App\WalletTransaction;
use App\Withdraw;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixData extends Command
{

    protected $signature = 'fix:data {table}';

    protected $description = 'fix dirty data by table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($table = $this->argument('table')) {
            return $this->$table();
        }
        return $this->error("必须提供你要修复数据的table");
    }
    public function articles(){

//        //修复抖音视频分类
//        $categoryDouyin = Category::whereName('抖音合集')->first();
//        $categoryHot    = Category::whereName('我要上热门')->first();
//        if($categoryDouyin){
//            Article::where('category_id',$categoryDouyin->id)->chunk(100,function($articles) use ($categoryDouyin,$categoryHot){
//                foreach ($articles as $article){
//                    $article->category_id = $categoryHot->id;
//                    $article->save(['timestamps' => false]);
//                    $article->categories()->sync([$categoryHot->id]);
//                }
//            });
//        }
//        if($categoryHot){
//            Article::where('category_id',$categoryHot->id)->chunk(100,function($articles) use ($categoryHot){
//                foreach ($articles as $article){
//                    $article->categories()->sync([$categoryHot->id]);
//                }
//            });
//        }

        //修复抖音视频title, body，description, tags
        Article::where('source_url','like','https://v.douyin.com/%')->chunk(100,function($articles){
            foreach ($articles as $article){
                $video = $article->video;
                if(!$video){
                    continue;
                }
                $json = $video->json;
                if(!$json){
                    continue;
                }
                $json = json_decode($json,true);
                $desc  = Arr::get($json,'metaInfo.item_list.0.desc','');
                $this->warn($desc);
                //去除抖音@标签
                $desc = preg_replace('/@([\w]+)/u','',$desc);
                preg_match_all('/#([\w]+)/u',$desc,$topicArr);
                $desc = preg_replace('/#([\w]+)/u','',$desc);
                $desc = trim($desc);

                $article->title         = $desc;
                $article->body          = $desc;
                $article->description   = $desc;
                $article->save(['timestamps' => false]);

                if($topicArr[1]){
                    $tags = [];
                    foreach ($topicArr[1] as $topic){
                        if(Str::contains($topic,'抖音')){
                            continue;
                        }
                        $tag = Tag::firstOrCreate([
                            'name' => $topic
                        ],[
                            'user_id' => 1
                        ]);
                        $tags[] = $tag->id;
                    }
                    $article->tags()->sync($tags);
                }
                $this->info($desc);
            }
        });

    }

    public function video()
    {

        //保存黑屏视频
        if (Storage::cloud()->exists('video/1.mp4')) {
            Storage::cloud()->move('video/1.mp4', 'video/1_old.mp4');
        }
        Storage::cloud()->put('video/1.mp4', @file_get_contents('http://cos.dianmoge.com/video/1.mp4'));
        Storage::cloud()->put('video/1.mp4.0_0.p0.jpg', @file_get_contents('http://cos.dianmoge.com/video/1.mp4.0_0.p0.jpg '));

        //下架Video_id的Article
        $video = Video::find(1);
        //黑屏视频与图片处理 video id处理
        if ($video) {
            $article         = $video->article;
            $article->status = -1;
            $article->sunmit = -1;
            $article->save();

            $video->user_id  = 1;
            $video->title    = '黑屏视频';
            $video->path     = 'video/1.mp4';
            $video->cover    = 'video/12.98.jpg';
            $video->duration = 15;
            $video->hash     = '4073b0265f5d794b5fd5653e2cf18dae';
            $video->setJsonData('covers', ["video/1.mp4.0_0.p0.jpg"]);
            $video->setJsonData('cover', 'video/1.mp4.0_0.p0.jpg');
            $video->save();
        } else {
            $video           = new Video();
            $video->user_id  = 1;
            $video->title    = '黑屏视频';
            $video->path     = 'video/1.mp4';
            $video->cover    = 'video/12.98.jpg';
            $video->duration = 15;
            $video->hash     = '4073b0265f5d794b5fd5653e2cf18dae';
            $video->setJsonData('covers', ["video/1.mp4.0_0.p0.jpg"]);
            $video->setJsonData('cover', 'video/1.mp4.0_0.p0.jpg');
            $video->setJsonData('width', 576);
            $video->setJsonData('height', 1024);
            $video->save();
        }
    }

    public function withdraws()
    {
        \DB::beginTransaction();
        try
        {
            //此区间的数据由于没有重启线上的提现队列导致信息丢失
            $withdraws = Withdraw::whereBetween('id', [597, 647])
                ->where('status', 1)->orderBy('id', 'asc')->get();
            foreach ($withdraws as $withdraw) {
                $wallet = $withdraw->wallet;

                $transaction            = new Transaction();
                $transaction->wallet_id = $withdraw->wallet_id;
                $transaction->type      = '兑换';
                $transaction->remark    = '提现';
                $transaction->status    = '已支付';
                $transaction->amount    = -1 * ($withdraw->amount);

                $latestTransaction = $wallet->transactions()
                    ->orderBy('id', 'desc')->first();

                $transaction->balance = $latestTransaction->balance - $withdraw->amount;

                $transaction->created_at = $withdraw->created_at;
                $transaction->save();
                $withdraw->transaction_id = $transaction->id;
                $withdraw->save(['timestamps' => false]);
            }

            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
        }
    }

    //合并WalletTransaction与Transaction表
    public function mergeWalletTransactionToTransaction()
    {

        $this->info('merge WalletTransaction To Transaction start...');

        //合并失败的WalletTransaction IDs
        $failIds = [];

        WalletTransaction::orderBy('id', 'asc')->chunk(100, function ($walletTransactions) use (&$failIds) {
            foreach ($walletTransactions as $walletTransaction) {
                \DB::beginTransaction();
                $id = $walletTransaction->id;
                try
                {
                    //TODO 幂等操作

                    $newTran             = new Transaction();
                    $newTran->wallet_id  = $walletTransaction->wallet_id;
                    $newTran->amount     = $walletTransaction->amount;
                    $newTran->balance    = $walletTransaction->balance;
                    $newTran->remark     = $walletTransaction->remark;
                    $newTran->created_at = $walletTransaction->created_at;
                    $newTran->updated_at = $walletTransaction->updated_at;
                    $newTran->type       = '兑换';
                    if ($walletTransaction->remark == '智慧点兑换') {
                        $newTran->status = '已兑换';
                    } else {
                        $newTran->status = '已支付';
                    }
                    $newTran->save(['timestamps' => false]);

                    if ($walletTransaction->remark != '智慧点兑换') {
                        //兼容withdraw中的transaction_id变动
                        $withdraw                 = Withdraw::where('transaction_id', $id)->firstOrFail();
                        $withdraw->transaction_id = $newTran->id;
                        $withdraw->save(['timestamps' => false]);
                    }

                    \DB::commit();
                } catch (\Exception $e) {
                    $failIds[] = $id;
                    \DB::rollBack();
                }
            }
        });
        $this->info('合并失败数:' . count($failIds));
        $this->info('merge WalletTransaction To Transaction finished...');

    }

    public function golds()
    {
        Gold::chunk(100, function ($golds) {
            foreach ($golds as $gold) {
                $user            = $gold->user;
                $goldWallet      = $user->goldWallet;
                $gold->wallet_id = $goldWallet->id;
                $gold->save(['timestamps' => false]);
            }
        });
        $this->info('fix golds finished...');
    }

    public function videoCover()
    {
        $articles = Article::whereBetween('id', [4337, 4360])->get();
        foreach ($articles as $article) {
            if (!$article->video_id) {
                continue;
            }
            $article->image_url = 'http://cos.dianmoge.com/video/' . $article->video_id . '.mp4.0_0.p0.jpg';
            $article->save(['timestamps' => false]);
        }
    }

    public function videos()
    {
        $count = 0;
        Article::orderBy('id', 'desc')->where('type', 'video')->whereStatus(1)->chunk(100, function ($articles) use (&$count) {

            foreach ($articles as $article) {
                $video = $article->video;

                if (!$video) {
                    continue;
                }
                if ($video->duration > 60) {
                    $count++;
                    $article->status = 0;
                    $article->save(['timestamps' => false]);
                    $this->info('video_id:' . $video->id);
                }

                // $url      = $video->path;
                // if(Str::startsWith($url,['video','/video','videos','/videos'])){
                //     $url = \Storage::cloud()->url($video->path);
                // }
                // $res      = get_headers($url, true);
                // $filesize = round($res['Content-Length'] / 1024 / 1024, 2); //四舍五入获取文件大小，单位M
                // $this->info($filesize);
                // //下架视频大家超过3M的视频
                // if ($filesize > 2) {
                //     $article->status = 0;
                //     $article->save(['timestamps' => false]);
                //     $this->info($video->id);
                // }
            }
        });
        $this->info($count);
        $this->info('fix videos finished...');
    }

    public function articleVieoCover()
    {
        $this->info('fix videos ...');
        // $videos = Video::where('status', '!=', -1);
        $articles = Article::where('status', '!=', -1);

        $articles->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $video = $article->video;

                if (!empty($video) && $video->status != -1) {
                    $cover = $video->cover;
                    if (\str_contains($cover, ['vod2.'])) {
                        $covers = $video->JsonData('covers');

                        $cosCovers = [];
                        $cosCover  = [];

                        if (empty($covers)) {
                            $video->setJsonData('covers', [$cover]);
                            $covers = $video->JsonData('covers');
                        }

                        foreach ($covers as $index => $cover) {
                            //有些地址会超时,此函数有问题
                            $url_status     = @get_headers($cover, 1);
                            $http_ok_status = false;
                            //地址获取是否有问题
                            if ($url_status) {
                                //判断是否是200状态吗
                                if (str_contains($url_status[0], "200")) {
                                    $http_ok_status = true;
                                } else if (str_contains($url_status[0], "301")) {
                                    //判断是否是重定向
                                    if (str_contains($url_status[1], "200")) {
                                        $http_ok_status = true;
                                    } else {
                                        $this->error("错误状态" . $url_status[0]);
                                    }
                                }
                            }

                            if ($http_ok_status && \str_contains($cover, ['vod2.'])) {
                                $sub_cover_str          = $video->id . '.' . $index;
                                $localImagePathTemplate = storage_path('app/public/video/' . '%s.jpg');
                                $localCoverPath         = sprintf($localImagePathTemplate, $sub_cover_str);

                                // \Storage::disk('public')->put($localCoverPath, file_get_contents($cover));
                                // $local_cover = \Storage::disk('public')->get($localCoverPath);
                                // $cosDisk     = \Storage::cloud();

                                $cos_storage_cover = '/storage/video/' . $sub_cover_str . '.jpg';
                                // $cosDisk->put($cos_storage_cover, $local_cover);
                                $cosCover[] = $cos_storage_cover;
                                // $cosCovers[] = \Storage::cloud()->url($cos_storage_cover);
                            }
                        }

                        $video->cover = (!empty($cosCover)) ? $cosCover[0] : null;
                        $video->setJsonData('covers', $cosCovers);
                        $video->timestamps = false;
                        $video->save();
                        $article = $video->article;
                        if (!empty($article)) {
                            if (empty($video->cover)) {
                                $article->status = -1;
                            } else {
                                $article->cover_path = $video->cover;
                            }

                            $article->timestamps = false;
                            $article->save();
                            $this->info($video->id . '视频的封面' . $video->cover . '文章' . $article->id . '的封面' . $article->cover_path);
                        }

                    }

                } else {
                    $article->status     = -1;
                    $article->timestamps = false;
                    $article->save();
                    $this->info('文章' . $article->id . '的状态' . $article->status);
                }
            }

        });

    }

    public function categories()
    {
        // Article

        $articles = Article::whereIn('type', ['video', 'post'])->whereNotNull('category_id');

        $articles->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $categories = $article->hasCategories()->pluck('category_id')->toArray();
                $category   = $article->category;
                if (!in_array($category->id, $categories)) {
                    array_push($categories, $category->id);
                    $category_ids = array_unique($categories);

                    $result = [];
                    foreach ($category_ids as $categoryId) {
                        $result[intval($categoryId)] = [
                            'submit' => '已收录',
                        ];
                    }
                    $article->hasCategories()->sync($result);
                }

                foreach ($article->categories as $category) {
                    $this->info($article->id . '号文章的' . $category->id . 'id以及name' . $category->name);
                }
            }
        });
    }

    public function follows()
    {
        $builder = User::where('status', User::STATUS_ONLINE);
        $builder->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $this->info($user->id . '修复前，粉丝数：' . $user->count_follows);
                $this->info($user->id . '修复前，关注数：' . $user->count_followings);

                $user->count_followings = $user->followings()->where('followed_type', 'users')->count();
                $user->count_follows    = $user->follows()->where('followed_type', 'users')->count();
                $user->save();

                $this->info('修复后，粉丝数：' . $user->count_follows);
                $this->info('修复后，关注数：' . $user->count_followings);
            }
        });
    }


    public function content($article)
    {
        $body = $article->body;
        $preg = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
        preg_match_all($preg, $body, $matchs);
        $image_tag = $matchs[0][0];
        $image_url = $matchs[1][0];
        $preg      = "/.*?thumbnail_(\d+)/";
        preg_match_all($preg, $image_url, $matchs);
        $video_id = $matchs[1][0];
        if (!empty($image_url)) {
            $article->body      = str_replace($image_tag, '', $body);
            $article->status    = -1;
            $article_categories = $article->categories()->get();
            $newArticle         = Article::where('video_id', $video_id)->first();
            if ($newArticle) {
                foreach ($article_categories as $category) {
                    $newArticle->categories()->attach([$category->id => [
                        'created_at' => $category->pivot->created_at,
                        'updated_at' => $category->pivot->updated_at,
                        'submit'     => $category->pivot->submit,
                    ]]);
                }
            }
            $article->save();
            $this->info('Article Id:' . $article->id . ' fix success');
        }
    }

    public function collections()
    {
        $this->info('fix collections ...');
        Collection::chunk(100, function ($collections) {
            foreach ($collections as $conllection) {
                $conllection_id = $conllection->id;
                if (count($conllection->articles()->pluck('article_id')) > 0) {
                    $article_id_arr = $conllection->articles()->pluck('article_id');
                    foreach ($article_id_arr as $article_id) {
                        $article                = Article::find($article_id);
                        $article->collection_id = $conllection_id;
                        $article->save();
                        $conllection->count_words += $article->count_words;
                        $this->info('Artcile:' . $article_id . ' corresponding collections:' . $conllection_id);
                    }
                    $conllection->count = count($article_id_arr);
                    $conllection->save();
                }
                //
            }
        });
        $this->info('fix collections success');
    }

    public function article_comments()
    {
        //修复Article评论数据
        $this->info('fix article comments...');
        Comment::whereNull('comment_id', 'and', true)->chunk(100, function ($comments) {
            foreach ($comments as $comment) {
                if (empty(Comment::find($comment->comment_id))) {
                    $article_id = $comment->commentable_id;
                    $comment->delete();
                    $this->info('文章： https://l.ainicheng.com/article/' . $article_id);
                }
            }
        });
        $this->info('fix articles count_comments...');
        //修复Article评论统计数据
        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $article->count_replies  = $article->comments()->count();
                $article->count_comments = $article->comments()->max('lou');
                $article->save();
            }
        });
        $this->info('fix success');
    }

    public function visits()
    {
        //获取视频的最大ID
        $max_video_id = Video::max('id');

        $index         = 0;
        $success_index = 0;
        $failed_index  = 0;

        $visits = Visit::where('visited_type', 'videos')->where('visited_id', '>', $max_video_id)->get();
        foreach ($visits as $visit) {
            $index++;
            try {
                $video_id = Article::findOrFail($visit->visited_id)->video_id;
            } catch (\Exception $e) {
                //如果这条记录不存在的话 删除掉这条浏览记录
                $visit_id = $visit->id;
                $visit->delete();
                $failed_index++;
                $this->error('visits ID:' . $visit_id . ' delete');
                continue;
            }

            $visit->visited_id = $video_id;
            $visit->timestamps = false;
            $visit->save();
            $success_index++;

            $this->info(env('APP_DOMAIN') . ' visits Id:' . $visit->id . ' fix success');
        }

        $this->info('总共fix数据:' . $index . '条,成功:' . $success_index . ',失败:' . $failed_index);
    }

    public function actions()
    {
        $this->info('fix action status ....');
        $fix_count    = 0;
        $delete_count = 0;
        //给actions表中 增加status 属性 fix数据
        \App\Action::chunk(100, function ($actions) use (&$fix_count, &$delete_count) {
            foreach ($actions as $action) {
                //获取对应的多态关联关系
                $actionable = $action->actionable;
                $action_id  = $action->id;
                //存在的数据fix 不存在的数据直接删除
                if ($actionable) {
                    switch ($action->actionable_type) {
                        case 'articles':
                            if ($actionable->status == 1) {
                                $action->status = 1;
                            }
                            break;

                        case 'comments':
                            if ($actionable->commentable_type == 'articles') {
                                if ($actionable->article->status == 1) {
                                    $action->status = 1;
                                }
                            }
                            break;

                        case 'favorites':
                            if ($actionable->faved_type == 'articles') {
                                if ($actionable->article->status == 1) {
                                    $action->status = 1;
                                }
                            } else if ($actionable->faved_type == 'videos') {
                                if ($actionable->video->article->status == 1) {
                                    $action->status = 1;
                                }
                            }
                            break;
                        case 'follows':
                            if ($actionable->followed_type == 'categories') {
                                if ($actionable->category) {
                                    $action->status = 1;
                                }
                            } else if ($actionable->followed_type == 'collections') {
                                if ($actionable->collection->status == 1) {
                                    $action->status = 1;
                                }
                            } else if ($actionable->followed_type == 'users') {
                                if ($actionable->user) {
                                    $action->status = 1;
                                }
                            }
                            break;
                        case 'likes':
                            if ($actionable->liked_type == 'articles') {
                                if ($actionable->article->status) {
                                    $action->status = 1;
                                }
                            } else if ($actionable->liked_type == 'comments') {
                                $comment = $actionable->comment;
                                if ($comment->commentable_type == 'articles') {
                                    if ($comment->article->status == 1) {
                                        $action->status = 1;
                                    }
                                }
                            }
                            break;
                    }

                    $fix_count++;

                    $this->info('action id.' . $action_id . ' fix success');
                } else {
                    $action->status = -1;
                    $delete_count++;
                    $this->error('action id.' . $action_id . ' fix failed');
                }
                $action->timestamps = false;
                $action->save();

            }
        });
        $this->info('fix action end');

    }

    public function users()
    {
        $this->info('修复 静默注册的手机号和原账号的手机号 相同');

        $user = User::find('2050');
        $this->warn('修复前，手机号为：' . $user->phone);
        $user->phone   = null;
        $user->account = 13327347555;
        $user->save();

        $this->warn('修复后，手机号为：' . $user->phone ?? '空');
    }

    public function notifications()
    {
        $this->info('fix notifications ....');
        DB::table('notifications')->whereType('App\Notifications\ArticleLiked')->orderByDesc('id')->chunk(100, function ($notifications) {
            foreach ($notifications as $notification) {
                $data = json_decode($notification->data);
                if (strpos($data->body, '视频') !== false && strpos($data->title, '《》') !== false) {
                    try {
                        $article       = Article::findOrFail($data->article_id);
                        $article_title = $article->title ?: $article->video->title;
                        // 标题 视频标题都不存在 则取description
                        if (empty($article_title)) {
                            $article_title = $article->summary;
                        }
                        $notification->timestamps = false;
                        $result                   = DB::table('notifications')->where('id', $notification->id)
                            ->update(
                                [
                                    'data->article_title' => $article_title,
                                    'data->title'         => '《' . $article_title . '》',
                                ]
                            );
                        if ($result) {
                            $this->info('notification ' . $notification->id . ' fix success');
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        });
        $this->info('fix success');
    }

    public function transactions()
    {
        Transaction::whereType('打赏')->orderByDesc('id')->chunk(100, function ($transactions) {
            foreach ($transactions as $transaction) {
                if (strpos($transaction->remark, '赏元') !== false) {
                    $remark                  = str_replace('赏元', '赏' . intval($transaction->amount) . '元', $transaction->remark);
                    $transaction->remark     = $remark;
                    $transaction->timestamps = false;
                    $transaction->save();
                    $this->info('transaction ' . $transaction->id . ' fix success');
                }
            }
        });
    }
}
