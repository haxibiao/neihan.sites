<?php

namespace App\Console\Commands;

use App\Article;
use App\BadWord;
use App\Contribute;
use App\Gold;
use App\Jobs\ProcessSpider;
use App\OAuth;
use App\Transaction;
use App\User;
use App\UserRetention;
use App\Video;
use App\Visit;
use App\Wallet;
use App\WalletTransaction;
use App\Withdraw;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\PushUrlCacheRequest;
use TencentCloud\Vod\V20180717\VodClient;
use Vod\Model\VodUploadRequest;
use Vod\VodUploadClient;

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

    public function failWithdraws(){
        $withdraws = Withdraw::whereIn('id',[5486,5485])->get();
        foreach ($withdraws as $withdraw){
            //重新查询锁住该记录更新
            $wallet   = $withdraw->wallet;
            $user     = $wallet->user;

            DB::beginTransaction(); //开启事务
            try {
                //1.更改提现记录
                $withdraw->status = Withdraw::FAILURE_WITHDRAW;
                $withdraw->remark = '提现失败';
                $withdraw->save();

                // 2.退回提现贡献点
                Contribute::create([
                    'user_id' => $user->id,
                    'remark'  => '提现失败返回贡献值',
                    'amount'  => 128,
                    'contributed_id' => $withdraw->id,
                    'contributed_type' => 'withdraws',
                ]);
                //事务提交
                DB::commit();
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback(); //数据回滚
            }
        }
    }

    /**
     * 修复抖音抓取视频的描述信息
     */
    public function fixDescription()
    {
        Article::whereNotNull('source_url')->where('video_id', '<>', 1)->chunk(1000, function ($articles) {
            foreach ($articles as $article) {
                $video = $article->video;
                if (!$video) {
                    continue;
                }
                if (!$video->json) {
                    continue;
                }
                $json = json_decode($video->json, true);
                $desc = Arr::get($json, 'metaInfo.item_list.0.desc', null);
                if (is_null($desc)) {
                    continue;
                }
                $desc = str_replace(['#在抖音，记录美好生活#', '@抖音小助手', '抖音', 'dou', 'Dou', 'DOU', '抖音助手'], '', $desc);
                $this->info($desc);
                $article->description = $desc;
                $article->title       = $desc;
                $article->body        = $desc;
                $article->save(['timestamps' => false]);
            }
        });
    }

    public function contribute()
    {
        Contribute::chunk(100,
            function ($contributes) {
                foreach ($contributes as $contribute) {
                    $contributed = $contribute->contributed_type;

                    if ($contributed == "articles") {
                        $contribute->remark = '发布视频奖励';
                    }
                    if ($contributed == "AD") {
                        $contribute->remark = '观看广告奖励';
                    }

                    if ($contributed == "AD_VIDEO") {
                        $contribute->remark = '观看激励视频奖励';
                    }
                    if ($contributed == "issues") {
                        $contribute->remark = '悬赏奖励';
                    }
                    if ($contributed == "usertasks") {
                        $contribute->remark = '任务奖励';
                    }

                    $contribute->timestamps = false;
                    $this->info('id' . $contribute->id . 'remark' . $contribute->remark);
                    $contribute->save();
                }
            });
    }

    public function userActivities()
    {
        //最近90天的日活统计信息(不包含今天)
        $endOfDay = Carbon::yesterday();
        $cacheKey = 'nova_user_activity_num_of_%s';
        for ($i = 0; $i <= 90; $i++) {
            $count = Visit::whereDate('created_at', $endOfDay)->distinct()->count('user_id');
            $key   = sprintf($cacheKey, $endOfDay->toDateString());
            $this->info($endOfDay->toDateString());
            $this->info($count);
            cache()->store('database')->forever($key, $count);
            $endOfDay = $endOfDay->subDay(1);
        }
    }

    public function articles()
    {
        $count = 0;

        Article::where('source_url', 'like', 'https://v.douyin.com%')
            ->where('submit', Article::SUBMITTED_SUBMIT)->chunk(1000, function ($posts) use (&$count) {
            foreach ($posts as $post) {
                $videoId = $post->video_id;
                //截图正常的视频则跳过
                if (Str::contains($post->cover_path, $videoId)) {
                    continue;
                }
                $video = $post->video;
                $this->info($post->id);
                if ($video) {
                    $count++;
                    $post->cover_path = $video->cover;
                    $post->save(['timestamps' => false]);
                }
            }
        });
    }

    //修复黑屏视频
    public function articles2()
    {
        $count = 0;
        Article::where('source_url', 'like', 'https://v.douyin.com%')
            ->where('video_id', 1)
            ->chunk(1000, function ($posts) use (&$count) {
                foreach ($posts as $post) {
                    $shareMsg = $post->description . ' ' . $post->source_url;
                    ProcessSpider::dispatch($post, $shareMsg)->onQueue('spider');
                }
            });
    }

    public function articles3()
    {
        $count = 0;
        Article::where('description', 'like', '%mp4%')
            ->chunk(1000, function ($posts) use (&$count) {
                foreach ($posts as $post) {
                    //软删除
                    $post->delete();
                    $this->info($post->id);
                    $count++;
                }
            });
        $this->info($count);
    }

    public function fixContributeRemark()
    {
        $contributes = Contribute::all();
        foreach ($contributes as $contribute) {

            $contributed = $contribute->contributed_type;
            if ($contributed == "AD_VIDEO") {
                $contribute->remark = '观看激励视频奖励';
            }
            $contribute->timestamps = false;
            echo 'id' . $contribute->id;

            $contribute->save();
        }
    }

    public function retentions()
    {
        User::with('visits')->chunk(100, function ($users) {
            foreach ($users as $user) {

                $registed_at = $user->created_at;
                if (!$registed_at) {
                    continue;
                }
                //次日留存
                $next_day_retention_at = $registed_at->addDay(1);
                $is_next_day_retention = $user->visits()->whereDate('created_at', $next_day_retention_at)->count() > 0;
                if ($is_next_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->next_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //3日留存
                $third_day_retention_at = $registed_at->addDay(3);
                $is_third_day_retention = $user->visits()->whereDate('created_at', $third_day_retention_at)->count() > 0;
                if ($is_third_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->third_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //5日留存
                $fifth_day_retention_at = $registed_at->addDay(5);
                $is_fifth_day_retention = $user->visits()->whereDate('created_at', $fifth_day_retention_at)->count() > 0;
                if ($is_fifth_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->fifth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //7日留存
                $sixth_day_retention_at = $registed_at->addDay(7);
                $is_sixth_day_retention = $user->visits()->whereDate('created_at', $sixth_day_retention_at)->count() > 0;
                if ($is_sixth_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->sixth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //30日留存
                $month_retention_at = $registed_at->addDay(7);
                $is_month_retention = $user->visits()->whereDate('created_at', $month_retention_at)->count() > 0;
                if ($is_month_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->month_retention_at = $user->created_at;
                    $retention->save();
                }
            }
        });
    }

    public function fixUnUploadtoVodVideos()
    {
        Article::with('video')->whereNotNull('source_url')
            ->whereNotNull('video_id')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $video = $article->video;
                if (!$video || $video->disk != 'local') {
                    continue;
                }
                $this->info($article->id);
                $json = json_decode($video->json, true);

                $shareMsg = Arr::get($json, 'metaInfo.item_list.0.desc') . ' ' . $article->source_url;

                //队列开始处理爬虫视频
                ProcessSpider::dispatch($article, $shareMsg)->onQueue('spider');
            }
        });
    }

    /**
     * 1.VOD视频PUSH到线上数据库
     */
    public function uploadVod()
    {
        $vodClassIds = [
            'dianmoge'     => 621049,
            'dongdianyao'  => 621585,
            'ainicheng'    => 621586,
            'dongdianfa'   => 621587,
            'youjianqi'    => 621588,
            'dongmeiwei'   => 621589,
            'dongdaima'    => 621590,
            'dongqizhi'    => 621591,
            'tongjiuxiu'   => 621592,
            'dongdianmei'  => 621593,
            'haxibiao'     => null,
            'qunyige'      => 621594,
            'dongdianyi'   => 621595,
            'caohan'       => 621596,
            'gba-port'     => 621597,
            'gba-life'     => 621598,
            'dongwaiyu'    => 621599,
            'dongyundong'  => 621600,
            'dongwuli'     => 621601,
            'jucheshe'     => 621602,
            'dongshouji'   => 621607,
            'dongdiancai'  => 621608,
            'dongshengyin' => 621609,
            'diudie'       => 621610,
            'dongdezhuan'  => 621611,
            'dongmiaomu'   => 619195,
        ];
        Article::whereNotNull('video_id')->where('status', 1)->where('submit', 1)->chunk(100,
            function ($articles) use ($vodClassIds) {
                foreach ($articles as $article) {
                    $video = $article->video;
                    if (!$video) {
                        continue;
                    }
                    //不处理已经上传到VOD的视频
                    if (Str::contains($video->path, 'vod2')) {
                        continue;
                    }
                    $client = new VodUploadClient("AKIDKZeYH6uMdqyxkxKyhFuQ0W5ThliVtWlq",
                        "61nNlyzqWxLbgaIpBMPM8lCWfeSAkEaq");
                    $client->setLogPath(storage_path('/logs/vod_upload.log'));
                    $req = new VodUploadRequest();
                    if (\str_contains($video->url, 'cos')) {
                        Storage::put($video->path, @file_get_contents($video->url));
                    } else {
                        $result = @file_get_contents(\Storage::disk('cosv5')->url($video->path));
                        if (!$result) {
                            $this->error($video->id);
                            continue;
                        }
                        Storage::put($video->path, $result);
                    }

                    try {
                        Storage::put($article->cover_path,
                            @file_get_contents(\Storage::disk('cosv5')->url($article->cover_path)));
                        $req->MediaFilePath = storage_path('app/public/' . $video->path);
                        //!!! 注意替换这个分类ID
                        $req->ClassId       = $vodClassIds[env('APP_NAME')];
                        $req->CoverFilePath = storage_path('app/public/' . $article->cover_path);
                        $req->CoverType     = 'jpg';
                        $rsp                = $client->upload("ap-guangzhou", $req);
                        echo "MediaUrl -> " . $rsp->MediaUrl . "\n";
                        $this->info($video->id);
                        $video->disk         = 'vod';
                        $video->qcvod_fileid = $rsp->FileId;
                        $video->path         = $rsp->MediaUrl;
                        $video->save(['timestamps' => false]);
                    } catch (\Exception $e) {
                        // 处理上传异常
                        $this->error($video->id);
                        echo $e;
                        continue;
                    }
                }
            });
    }

    /**
     * 2.VOD视频预热
     */
    public function pushUrlCache()
    {
        Video::where('disk', 'vod')->chunk(20, function ($videos) {
            $videoUrl = [];
            foreach ($videos as $video) {
                $videoUrl[] = $video->url;
            }
            if ($videoUrl) {
                try {
                    $cred = new Credential("AKIDKZeYH6uMdqyxkxKyhFuQ0W5ThliVtWlq",
                        "61nNlyzqWxLbgaIpBMPM8lCWfeSAkEaq");
                    $httpProfile = new HttpProfile();
                    $httpProfile->setEndpoint("vod.tencentcloudapi.com");

                    $clientProfile = new ClientProfile();
                    $clientProfile->setHttpProfile($httpProfile);
                    $client = new VodClient($cred, "ap-guangzhou", $clientProfile);

                    $req = new PushUrlCacheRequest();

                    $params = '{"Urls":["' . implode('","', $videoUrl) . '"]}';
                    $req->fromJsonString($params);

                    $resp = $client->PushUrlCache($req);

                    print_r($resp->toJsonString());
                } catch (TencentCloudSDKException $e) {
                    echo $e;
                }
                sleep(1);
            }
        });
    }

    public function importVideo()
    {
        $videos = DB::connection('dianmoge_vod')->table('videos')->get();
        foreach ($videos as $video) {
            if (Str::contains($video->path, '1254284941')) {
                $oldVideo               = Video::find($video->id);
                $oldVideo->path         = $video->path;
                $oldVideo->qcvod_fileid = $video->qcvod_fileid;
                $oldVideo->save(['timestamps' => false]);
                $this->info($video->id);
            }
        }
    }

    /**
     * 增加默认视频
     */
    public function video()
    {

        //保存黑屏视频
        if (Storage::cloud()->exists('video/1.mp4')) {
            Storage::cloud()->move('video/1.mp4', 'video/1_old.mp4');
        }
        Storage::cloud()->put('video/1.mp4', @file_get_contents('http://cos.dianmoge.com/video/1.mp4'));
        Storage::cloud()->put('video/1.mp4.0_0.p0.jpg',
            @file_get_contents('http://cos.dianmoge.com/video/1.mp4.0_0.p0.jpg '));

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
            $video->cover    = 'video/1.mp4.0_0.p0.jpg';
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

    public function BadWord()
    {
        $file     = dirname(__FILE__) . '/../../Helpers/BadWord/' . 'bad-word.txt';
        $text     = file_get_contents($file);
        $badWords = explode("\n", $text);
        $badWords = array_map(function ($text) {
            $text = base64_decode($text);
            $text = str_replace(["\n", "\r", "\n\r", '"', ';'], '', $text);
            return $text;
        }, $badWords);

        foreach ($badWords as $badWord) {
            $badword = BadWord::firstOrCreate([
                'word' => $badWord,
            ]);

            $this->info('id' . $badword->id . '的词' . $badword->word);
        }
    }

    /**
     * 修复转码视频
     */
    public function videos()
    {
        $count      = 0;
        $dispatcher = Video::getEventDispatcher();
        Video::unsetEventDispatcher();
        Video::chunk(100, function ($videos) {
            foreach ($videos as $video) {
                $path = $video->path;
                $hd   = $path . '.f30.mp4';
                $sd   = $path . '.f20.mp4';
                $ld   = $path . '.f10.mp4';
                if (Storage::cloud()->exists($hd)) {
                    $video->setJsonData('transcode_hd_mp4', $hd);
                    $video->path = $hd;
                    $video->save();
                }
                if (Storage::cloud()->exists($sd)) {
                    $video->setJsonData('transcode_sd_mp4', $sd);
                }
                if (Storage::cloud()->exists($ld)) {
                    $video->setJsonData('transcode_ld_mp4', $ld);
                }
            }
        });
        Video::setEventDispatcher($dispatcher);
        $this->info($count);
        $this->info('fix videos finished...');
    }

    public function withdraws()
    {
        \DB::beginTransaction();
        try {
            //提现成功：[953,976];
            $withdraws = Withdraw::whereBetween('id', [1012, 1030])->get();
            foreach ($withdraws as $withdraw) {
                $wallet = $withdraw->wallet;
                $amount = $withdraw->amount;

                $amount                 = -1 * $amount;
                $balance                = $wallet->balance + $amount;
                $transaction            = new Transaction();
                $transaction->wallet_id = $wallet->id;
                $transaction->type      = '提现';
                $transaction->remark    = '延迟扣款';
                $transaction->status    = '已支付';
                $transaction->balance   = $balance;
                $transaction->amount    = $amount;
                $transaction->save();

                $withdraw->status         = Withdraw::SUCCESS_WITHDRAW;
                $withdraw->remark         = '提现成功';
                $withdraw->transaction_id = $transaction->id;
                $withdraw->to_platform    = 'Alipay';
                $withdraw->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
        }
    }

    public function fixArticle()
    {
        $articles = Article::where('cover_path', 'video/12.98.jpg')->get();
        foreach ($articles as $article) {
            $cover               = sprintf('video/%s.mp4.0_0.p0.jpg', $article->video_id);
            $article->cover_path = $cover;
            $article->save(['timestamps' => false]);
            $video = $article->video;
            $video->setJsonData('covers', [$cover]);
            $video->setJsonData('cover', $cover);
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
                try {
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
                                } else {
                                    if (str_contains($url_status[0], "301")) {
                                        //判断是否是重定向
                                        if (str_contains($url_status[1], "200")) {
                                            $http_ok_status = true;
                                        } else {
                                            $this->error("错误状态" . $url_status[0]);
                                        }
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
                    $newArticle->categories()->attach([
                        $category->id => [
                            'created_at' => $category->pivot->created_at,
                            'updated_at' => $category->pivot->updated_at,
                            'submit'     => $category->pivot->submit,
                        ],
                    ]);
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
                            } else {
                                if ($actionable->faved_type == 'videos') {
                                    if ($actionable->video->article->status == 1) {
                                        $action->status = 1;
                                    }
                                }
                            }
                            break;
                        case 'follows':
                            if ($actionable->followed_type == 'categories') {
                                if ($actionable->category) {
                                    $action->status = 1;
                                }
                            } else {
                                if ($actionable->followed_type == 'collections') {
                                    if ($actionable->collection->status == 1) {
                                        $action->status = 1;
                                    }
                                } else {
                                    if ($actionable->followed_type == 'users') {
                                        if ($actionable->user) {
                                            $action->status = 1;
                                        }
                                    }
                                }
                            }
                            break;
                        case 'likes':
                            if ($actionable->liked_type == 'articles') {
                                if ($actionable->article->status) {
                                    $action->status = 1;
                                }
                            } else {
                                if ($actionable->liked_type == 'comments') {
                                    $comment = $actionable->comment;
                                    if ($comment->commentable_type == 'articles') {
                                        if ($comment->article->status == 1) {
                                            $action->status = 1;
                                        }
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
        // 修复逻辑： 3小时内创建的账户（缩小影响范围 ），uuid有旧的存在：
        $users = User::where('created_at', '>', now()->addHours(-3))->get();
        $this->info('3小时内新用户数:' . $users->count());
        foreach ($users as $user) {
            $uuid = $user->uuid ? $user->uuid : $user->account;
            $this->info("uuid:" . $uuid);

            $qb = User::whereAccount($uuid);
            if ($qb->count() > 1) {
                $oldUser = $qb->orderBy('id')->first();
                if ($oldUser) {
                    $this->info("old users count:" . $qb->count());
                    $this->comment("找到旧用户 $oldUser->id $oldUser->uuid $oldUser->name account:$oldUser->account");

                    $user->uuid   = null;
                    $user->status = -1;
                    $user->save();

                    $oldUser->uuid = $uuid;
                    $oldUser->save();

                    $token           = $user->api_token;
                    $user->api_token = str_random(60);
                    $user->save();

                    $oldUser->api_token = $token;
                    $oldUser->save();

                }

            }

        }
        // -  清空uuid (account 已存)
        // -  账户停用

        // 下次启动... 新的token...  新的User ...   还得 me 查询里给切换回去...
        // -  找的uuid的 旧User, 更新uuid
        // -  新的停用账户换个token
        // -  覆盖token, 新的token 覆盖旧的token...

    }

    public function notifications()
    {
        $this->info('fix notifications ....');
        DB::table('notifications')->whereType('App\Notifications\ArticleLiked')->orderByDesc('id')->chunk(100,
            function ($notifications) {
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

    public function withdrawJobs()
    {
        $count = 0;
        // $row   = DB::table('jobs')->where('queue', 'withdraws')->delete();
        // $this->info('提现队列JOB清理成功,清理数:' . $row);

        Withdraw::where('status', Withdraw::WAITING_WITHDRAW)->chunk(1000, function ($withdraws) use (&$count) {
            foreach ($withdraws as $withdraw) {
                dispatch(new \App\Jobs\ProcessWithdraw($withdraw->id))->onQueue('withdraws');
                $this->info('Withdraw Id:' . $withdraw->id . ' push queue success');
                $count++;
            }
        });

        $this->info('成功修复提现:' . $count);
    }

    public function payTest()
    {
        dump(class_exists('Yansongda\Pay\Pay'));
    }

    public function oAuths()
    {
        OAuth::where('oauth_type', 'wechat')->with('user')->chunk(1000, function ($oauths) {
            foreach ($oauths as $oauth) {
                $user   = $oauth->user;
                $wallet = $user->wallet;

                $payAccount = $wallet->pay_account;

                if (!is_email($payAccount) && !is_phone_number($payAccount)) {
                    $wallet->pay_account = null;
                    $wallet->save();
                }

            }
        });
    }

    public function wallets()
    {
        $data = Wallet::selectRaw('wechat_account,count(*) as bind_count')
            ->where('wechat_account', '!=', '')
            ->groupBy('wechat_account')
            ->having('bind_count', '>', 1)
            ->get();

        foreach ($data as $item) {
            $wechatAccount = $item->wechat_account;
            $wallets       = Wallet::where('wechat_account', $wechatAccount)->get();
            foreach ($wallets as $wallet) {
                $oauth = OAuth::where('user_id', $wallet->user_id)->where('oauth_type', 'wechat')->first();
                if (is_null($oauth)) {
                    $wallet->wechat_account = '';
                    $wallet->save();
                    $this->info('成功修复 Wallet Id:' . $wallet->id . ' 姓名:' . $wallet->real_name);
                }
            }
        }
    }

    public function spiders()
    {
        // $count    = 0;
        // $articles = \App\Article::where('source_url', 'like', '%v.douyin.com%')
        //     ->where('status', \App\Article::REVIEW_SUBMIT)
        //     ->chunk(1000, function ($artilces) use (&$count) {
        //         foreach ($artilces as $article) {
        //             $article->startSpider();
        //             $this->info('Article Id:' . $article->id . ' spider start');
        //             $count++;
        //         }
        //     });

        //这个hash有问题
        $hash  = 'd41d8cd98f00b204e9800998ecf8427e';
        $video = Video::where('hash', $hash)->first();
        if(!is_null($video)){
            $count = Article::where('video_id', $video->id)->update(['status' => Article::REFUSED_SUBMIT, 'submit' => Article::REFUSED_SUBMIT]);
        }

        $this->info('本次成功修复待处理爬虫:' . $count);

    }

}
