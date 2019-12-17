<?php

namespace App\Jobs;

use App\Article;
use App\Category;
use App\Contribute;
use App\Exceptions\GQLException;
use App\Gold;
use App\Notifications\ReceiveAward;
use App\Tag;
use App\Video;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Vod\Model\VodUploadRequest;
use Vod\VodUploadClient;

class ProcessSpider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 180;

    private $article;
    private $shareMsg;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article, String $shareMsg)
    {
        $this->article  = $article;
        $this->shareMsg = $shareMsg;
        $this->user     = $article->user;
    }

    /**
     * @throws GQLException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $shareMsg = $this->shareMsg;
        $link     = filterText($shareMsg)[0];
        $user     = $this->article->user;
        $article  = $this->article;

        //轮训爬虫服务器
        $prossserServices = [
            'gz01.haxibiao.com',
            'gz02.haxibiao.com',
            'gz03.haxibiao.com',
            'gz04.haxibiao.com',
            'gz05.haxibiao.com',
            'gz06.haxibiao.com',
            'gz07.haxibiao.com',
            'gz08.haxibiao.com',
        ];

        $endPoint = Arr::random($prossserServices);

        //轮训Job
        $getApi = 'http://' . $endPoint . '/simple-spider/index.php?url=' . $link;
        $data   = file_get_contents($getApi);
        $json   = json_decode($data, true);

        $description = Arr::get($json, 'data.raw.item_list.0.desc', '');
        $description = preg_replace('/@([\w]+)/u', '', $description);
        preg_match_all('/#([\w]+)/u', $description, $topicArr);
        $description = preg_replace('/#([\w]+)/u', '', $description);
        $description = trim($description);

        $content = $json['data'];
        if ($json['code'] != 200) {
            //删除该视频

            throw new Exception('视频上传失败!');
        }
        //保存视频信息
        $url = $content['video'];
        $raw = $content['raw'];

        if ($topicArr[1]) {
            $tags = [];
            foreach ($topicArr[1] as $topic) {
                if (Str::contains($topic, '抖音')) {
                    continue;
                }
                $tag = Tag::firstOrCreate([
                    'name' => $topic,
                ], [
                    'user_id' => 1,
                ]);
                $tags[] = $tag->id;
            }
            $article->tags()->sync($tags);
        }

        $hash  = md5_file($url);
        $video = Video::firstOrNew([
            'hash' => $hash,
        ]);
        $video->setJsonData('metaInfo', $raw);
        $video->setJsonData('server', $url);
        $video->user_id = $user->id;
        $video->title   = $description;
        $video->save();

        //本地存一份用于截图
        $cosPath     = 'video/' . $video->id . '.mp4';
        $video->path = $cosPath;
        Storage::disk('public')->put($cosPath, @file_get_contents($url));
        $video->disk = 'local'; //先标记为成功保存到本地
        $video->save();

        $article->video_id = $video->id;
        $article->save();

        //将视频上传到VOD
        $client = new VodUploadClient(config('tencentvod.' . config('app.name') . '.secret_id'), config('tencentvod.' . config('app.name') . '.secret_key'));
        $client->setLogPath(storage_path('/logs/vod_upload.log'));
        $req                = new VodUploadRequest();
        $req->MediaFilePath = storage_path('app/public/' . $cosPath);
        $req->ClassId       = intval(getVodConfig('class_id'));
        try {
            $rsp                 = $client->upload("ap-guangzhou", $req);
            $video->disk         = 'vod';
            $video->qcvod_fileid = $rsp->FileId;
            $video->path         = $rsp->MediaUrl;
            $video->save(['timestamps' => false]);
        } catch (\Exception $e) {
            // 处理上传异常
            \Log::error($e);
        }

        MakeVideoCovers::dispatchNow($video);

        $category = Category::firstOrNew([
            'name' => '我要上热门',
        ]);
        if (!$category->id) {
            $category->name_en = 'woyaoshangremeng';
            $category->status  = 1;
            $category->user_id = 1;
            $category->save();
        }

        //避免与Observer处理存在时间差导致重复创建
        $article              = $this->article;
        $article->video_id    = $video->id;
        $article->body        = $description;
        $article->title       = Str::limit($description, 150); //截取微博那么长的内容存简介;
        $article->description = Str::limit($description, 280); //截取微博那么长的内容存简介
        $article->status      = Article::STATUS_ONLINE; //FIXME 合并submit与status字段
        $article->submit      = Article::SUBMITTED_SUBMIT; //不直接上架
        $article->user_id     = $user->id;
        $article->category_id = $category->id;
        $article->review_id   = Article::makeNewReviewId();
        $article->save();

        $article->categories()->sync([$category->id]);

        //奖励用户
        $user->notify(new ReceiveAward('发布视频动态奖励', 10, $user, $article->id));
        Gold::makeIncome($user, 10, '发布视频动态奖励');
        Contribute::rewardUserVideoPost($user, $article, "发布视频动态奖励");

    }
}
