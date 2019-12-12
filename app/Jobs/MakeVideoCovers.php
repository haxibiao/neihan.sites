<?php

namespace App\Jobs;

use App\Article;
use App\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\PushUrlCacheRequest;
use TencentCloud\Vod\V20180717\VodClient;

class MakeVideoCovers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    // public $timeout = 600;  //need pcntl PHP extension!!!

    protected $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = $this->video;

        if(Str::contains($video->path,'vod')){
            $videoPath = $video->path;
            $video->makeCovers($videoPath);
            //CDN预热
            $this->pushUrlCacheRequest($video->path);
        }else{
            $videoPath = \Storage::cloud()->url($video->path);
            $video->makeCovers($videoPath,'cos');
        }

        $video = $this->video;

        //      更新对应动态
        $article = \App\Article::firstOrNew([
            'video_id' => $video->id,
        ]);

        $article->cover_path = $video->cover;
        $article->user_id = $video->user_id;

        if (!$article->type) {
            $article->type = 'video';
        }

        $article->status = Article::STATUS_ONLINE;
        $article->submit = Article::SUBMITTED_SUBMIT;

        $article->save();
    }

    public function pushUrlCacheRequest($url){
        //VOD预热
        $cred = new Credential(env('VOD_SECRET_ID'), env('VOD_SECRET_KEY'));
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("vod.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $client = new VodClient($cred, "ap-guangzhou", $clientProfile);
        $req = new PushUrlCacheRequest();
        $params = '{"Urls":["'.$url.'"]}';

        $req->fromJsonString($params);
        $resp = $client->PushUrlCache($req);
        print_r($resp->toJsonString());
    }
}
