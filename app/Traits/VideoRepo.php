<?php

namespace App\Traits;

use haxibiao\helpers\QcloudUtils;
use App\Jobs\MakeVideoCovers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\PushUrlCacheRequest;
use TencentCloud\Vod\V20180717\VodClient;

trait VideoRepo
{

    // VOD视频资源预热
    public function pushUrlCacheWithVODUrl($url)
    {
        //VOD预热
        $cred        = new Credential(config('tencentvod.' . config('app.name') . '.secret_id'), config('tencentvod.' . config('app.name') . '.secret_key'));
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("vod.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $client = new VodClient($cred, "ap-guangzhou", $clientProfile);
        $req    = new PushUrlCacheRequest();
        $params = '{"Urls":["' . $url . '"]}';

        $req->fromJsonString($params);
        return $client->PushUrlCache($req);
    }

    public function fillForJs()
    {
        $video        = $this;
        $video->url   = $video->url;
        $video->cover = $video->cover; //返回full uri

        //兼容旧接口
        $video->video_id  = $this->id;
        $video->video_url = $this->url;
        $video->image_url = $this->cover;

        return $video;
    }
    public function getPath()
    {
        //TODO: save this->extension, 但是目前基本都是mp4格式
        $extension = 'mp4';
        return '/storage/video/' . $this->id . '.' . $extension;
    }

    public function saveFile(UploadedFile $file)
    {
        $this->user_id = getUserId();
        $this->save(); //拿到video->id

        $cosPath     = 'video/' . $this->id . '.mp4';
        $this->path  = $cosPath;
        $this->hash  = md5_file($file->path());
        $this->title = $file->getClientOriginalName();
        $this->save();

        try {
            //本地存一份用于截图
            $file->storeAs(
                'video',
                $this->id . '.mp4'
            );
            $this->disk = 'local'; //先标记为成功保存到本地
            $this->save();

            //同步上传到cos
            $cosDisk = \Storage::cloud();
            $cosDisk->put($cosPath, \Storage::disk('public')->get('video/' . $this->id . '.mp4'));
            $this->disk = 'cos';
            $this->save();

            dispatch((new MakeVideoCovers($this)))->delay(now()->addMinute(1));
            return true;
        } catch (\Exception $ex) {
            \Log::error("video save exception" . $ex->getMessage());
        }
        return false;
    }

    public function saveWidthHeight($path)
    {
        $image  = getimagesize($path);
        $width  = $image["0"]; ////获取图片的宽
        $height = $image["1"]; ///获取图片的高

        $this->setJsonData('width', $width);
        $this->setJsonData('height', $height);
        $this->save();
    }

    public function recordAction()
    {
        if ($this->status > 0) {
            $action = \App\Action::updateOrCreate([
                'user_id'         => $this->user_id,
                'actionable_type' => 'videos',
                'actionable_id'   => $this->id,
            ]);
        }
    }

    public function pushUrlCacheRequest($url)
    {
        //VOD预热
        $cred        = new Credential(env('VOD_SECRET_ID'), env('VOD_SECRET_KEY'));
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("vod.tencentcloudapi.com");

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $client = new VodClient($cred, "ap-guangzhou", $clientProfile);
        $req    = new PushUrlCacheRequest();
        $params = '{"Urls":["' . $url . '"]}';

        $req->fromJsonString($params);
        $client->PushUrlCache($req);
    }

    public function processVod()
    {

        $videoInfo      = QcloudUtils::getVideoInfo($this->qcvod_fileid);
        $duration       = Arr::get($videoInfo, 'basicInfo.duration');
        $sourceVideoUrl = Arr::get($videoInfo, 'basicInfo.sourceVideoUrl');
        $this->path     = $sourceVideoUrl;
        $this->duration = $duration;
        $this->disk     = 'vod';
        $this->hash     = hash_file('md5', $sourceVideoUrl);
        $this->save();
        //触发截图操作
        MakeVideoCovers::dispatchNow($this);
    }
}
