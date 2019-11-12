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
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function __construct(Article $article,String $shareMsg)
    {
        $this->article      = $article;
        $this->shareMsg     = $shareMsg;
        $this->user         = $article->user;
    }

    /**
     * @throws GQLException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $shareMsg = $this->shareMsg;
        $link = filterText($shareMsg)[0];
        $user = $this->article->user;
        $article = $this->article;

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
        $getApi = 'http://' . $endPoint . '/simple-spider/index.php?url=' . $link ;
        $data = file_get_contents($getApi);
        $json   = json_decode($data, true);

        $description = Arr::get($json,'data.raw.item_list.0.desc','');

        $content = $json['data'];
        if($json['code'] != 200){
            throw new Exception('视频上传失败!');
        }
        //保存视频信息
        $url= $content['video'];
        $raw= $content['raw'];

        //去除@便签
        if(Str::contains($description,'@')){
            $description = Str::before($description,'@');
        }

        //抖音的分类信息维护到标签
        if(Str::contains($description,'#')){
            $desc = $description;
            $description = Str::before($desc,'#');

            //保存标签
            $tagStr = Str::after($desc,'#');
            if($tagStr){
                $tagStrs = explode('#',$tagStr);
                $tags = [];
                foreach ($tagStrs as $tagStr){
                    if(!$tagStr){
                        continue;
                    }
                    $tag = Tag::firstOrCreate([
                        'name' => $tagStr
                    ],[
                        'user_id' => 1
                    ]);
                    $tags[] = $tag->id;
                }
                $article->tags()->sync($tags);
            }
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

        //将视频上传到cos
        $cosDisk = Storage::cloud();
        $cosDisk->put($cosPath, Storage::disk('public')->get($cosPath));
        $video->disk = 'cos';
        $video->save();

        $category = Category::firstOrNew([
            'name' => '我要上热门'
        ]);
        if(!$category->id){
            $category->name_en  = 'woyaoshangremeng';
            $category->status   = 1;
            $category->user_id  = 1;
            $category->save();
        }
        //避免与Observer处理存在时间差导致重复创建
        $article = $this->article;
        $article->video_id    = $video->id;
        $article->body        = $description;
        $article->title       = Str::limit($description, 150); //截取微博那么长的内容存简介;
        $article->description = Str::limit($description, 280); //截取微博那么长的内容存简介
        $article->status      = 1;//FIXME 合并submit与status字段
        $article->submit      = Article::SUBMITTED_SUBMIT; //直接上架状态
        $article->user_id     = $user->id;
        $article->category_id = $category->id;
        $article->save();

        //奖励用户
        $user->notify(new ReceiveAward('发布视频动态奖励', 10, $user, $article->id));
        Gold::makeIncome($user, 10, '发布视频动态奖励');
        Contribute::rewardUserVideoPost($user,$article);

    }
}
