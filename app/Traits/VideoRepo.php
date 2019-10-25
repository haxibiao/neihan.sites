<?php

namespace App\Traits;

use App\Jobs\TakeScreenshots;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait VideoRepo
{
    public function takeSnapshot($force = false, $flag = true)
    {
        \App\Jobs\TakeScreenshots::dispatch($this, $force, $flag);
    }

    public function saveFile($file)
    {
        if ($file) {
            $this->user_id = getUserId();
            $this->save();

            $cosPath = 'video/' . $this->id . '.mp4';

            //保存视频地址
            $hash       = md5_file($file->path());
            $this->path = $cosPath;
            $this->hash = $hash;
            try {
                //本地存一份用于截图
                $file->storeAs(
                    'video', $this->id . '.mp4'
                );
                $this->disk = 'local'; //先标记为成功保存到本地
                $this->save();

                //同步上传到cos
                $cosDisk = \Storage::cloud();
                $cosDisk->put($cosPath, \Storage::disk('public')->get('video/' . $this->id . '.mp4'));
                $this->disk = 'cos';
                $this->save();

                //启动截取图片job
                TakeScreenshots::dispatch($this->id);

                return true;

            } catch (\Exception $ex) {
                \Log::error("video save exception" . $ex->getMessage());
            }

            //注释的原因：hashvod目前偶尔不稳定，留到下一版上线
            //如果不是线上环境则存储在本地
            //            $this->save_video_local($file);
            //
            //            $content = HashVod::upload(public_path($this->getPath()));
            //            $data    = json_decode($content, true);
            //
            //            if ($data['code'] != 200) {
            //                return false;
            //            }
            //            return false;
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

    public function makeCovers()
    {
        $video = $this;

        /* ===视频时长=== */
        $videoPath        = storage_path('app/public/video/' . $this->id . '.mp4');
        $cmd_get_duration = 'ffprobe -i ' . $videoPath . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
        $duration         = `$cmd_get_duration`;

        //等比例截取4张图片 20%，40%, 60%, 80%，
        $timeCodes = [
            bcmul(sprintf('%.2f', $duration), 0.2, 2),
            bcmul(sprintf('%.2f', $duration), 0.4, 2),
            bcmul(sprintf('%.2f', $duration), 0.6, 2),
            bcmul(sprintf('%.2f', $duration), 0.8, 2),
        ];

        $duration        = intval($duration);
        $video->duration = $duration;
        $video->save();

        /* ===视频封面=== */
        $localImagePathTemplate = storage_path('app/public/video/' . '%s.jpg');
        $covers                 = [];
        $cosCovers              = [];
        foreach ($timeCodes as $second) {

            $coverName = $video->id . $second;
            //视频封面输出地址
            $localCoverPath = sprintf($localImagePathTemplate, $coverName);

            $cmd = "ffmpeg -ss $second -i $videoPath -an -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $localCoverPath 2>&1";

            $exit_code = exec($cmd);
            \Log::info($exit_code);
            Storage::cloud()->put('/storage/video/' . $coverName . '.jpg', file_get_contents($localCoverPath));
            $covers[]    = '/app/public/video/' . $coverName . '.jpg';
            $cosCovers[] = \Storage::cloud()->url('/storage/video/' . $coverName . '.jpg');
        }
        if (count($covers)) {
            $video->timestamps = false;
            $video->disk       = 'cos';
            $video->status     = 1; //1代表文章可用，0代表草稿
            $video->cover      = $cosCovers[0];
            $this->setJsonData('covers', $cosCovers);
            $this->setJsonData('cover', $cosCovers[0]);
            $video->save();
            $video->saveWidthHeight(storage_path($covers[0]));

            //更新文章的状态
            $article = \App\Article::firstOrNew([
                'video_id' => $video->id,
            ]);

            $data = [
                'image_url' => $cosCovers[0],
                'user_id'   => $this->user_id,
            ];

            //video的title是文件的title
            if (empty($article)) {
                $data = array_merge($data, [
                    'description' => $this->title,
                    'body'        => $this->title,
                ]);
            }
            $article->forceFill($data)->save();
        }
    }

    public function syncVodProcessResult()
    {
        $flag = 0;
        //简单顾虑，视频地址确实是上传到了vod的
        $res = [];
        if (str_contains($this->path, 'vod')) {
            $res = QcloudUtils::getVideoInfo($this->qcvod_fileid);
            if (!empty($res['basicInfo']) && !empty($res['basicInfo']['duration'])) {
                $this->duration = $res['basicInfo']['duration'];
            }
            if (!empty($res['basicInfo']) && !empty($res['basicInfo']['coverUrl'])) {
                $this->cover = $res['basicInfo']['coverUrl'];
                $flag        = 1;
            }

            $covers = [];
            if (
                !empty($res['snapshotByTimeOffsetInfo']) &&
                !empty($res['snapshotByTimeOffsetInfo']['snapshotByTimeOffsetList'])
            ) {
                foreach ($res['snapshotByTimeOffsetInfo']['snapshotByTimeOffsetList'] as $snapInfo) {
                    if ($snapInfo['definition'] == 10) {
                        foreach ($snapInfo['picInfoList'] as $urlArr) {
                            $url      = $urlArr["url"];
                            $covers[] = ssl_url($url);
                        }
                    }
                }
            }
            if (empty($covers)) {
                $covers = [$this->cover];
                //没有封面数据，提交截图任务，下次编辑也许就能选截图了
                $this->makeCover();
            }
            $this->setJsonData('covers', $covers);

            if (!empty($res['transcodeInfo']) && !empty($res['transcodeInfo']['transcodeList'])) {
                $video_urls = [];
                foreach ($res['transcodeInfo']['transcodeList'] as $codeInfo) {
                    //同步其他码率的url
                    if (!empty($codeInfo['templateName'])) {
                        if (str_contains($codeInfo['templateName'], '流畅')) {
                            $video_urls['流畅'] = ssl_url($codeInfo['url']);
                        }
                        if (str_contains($codeInfo['templateName'], '标清')) {
                            $video_urls['标清'] = ssl_url($codeInfo['url']);
                        }
                        if (str_contains($codeInfo['templateName'], '高清')) {
                            $video_urls['高清'] = ssl_url($codeInfo['url']);
                            //默认播放url 用1280*
                            $this->path = ssl_url($codeInfo['url']);
                            $flag       = 2;
                        }
                        if (str_contains($codeInfo['templateName'], '全高清')) {
                            $video_urls['全高清'] = ssl_url($codeInfo['url']);
                        }
                    }
                }
                $this->setJsonData('video_urls', $video_urls);
            }
            //视频的宽和高
            if (!empty($res['metaData'])) {
                //duration
                $this->duration = $res['metaData']['duration'];
                //旋转率
                $this->setJsonData('rotate', $res['metaData']['rotate']);
                if (!empty($res['metaData']['videoStreamList'])) {
                    $videoStreamList = $res['metaData']['videoStreamList'][0];
                    $height          = $videoStreamList['height'];
                    $width           = $videoStreamList['width'];
                    $this->setJsonData('height', $height);
                    $this->setJsonData('width', $width);
                }
            }
            $this->save();
        }
        //如果res为空 或duration = 0 表示该视频有问题
        if (empty($res) || empty($res['basicInfo']['duration'])) {
            $flag = -1;
        }
        return $flag; //-1:有异常的文件, 0: 还没截图，1：有截图，2：有转码结果
    }

    public function publishPost()
    {
        //获得封面了，关联的视频动态发布出去
        if (!empty($this->cover)) {
            if ($article = $this->article) {
                //同步封面
                $article->image_url = $this->cover;
                $article->status    = 1;
                $article->save();

                //视频发布
                $this->status = 1;
                $this->save();

                //action
                $this->recordAction();
            }
        }
    }

    public function makeCover()
    {
        \QcloudUtils::makeCoverAndSnapshots($this->qcvod_fileid, $this->duration);
    }

    public function transCode()
    {
        \QcloudUtils::convertVodFile($this->qcvod_fileid, $this->duration);
    }

    public function startProcess()
    {
        //截图前需要先获取到duration
        $this->syncVodProcessResult();
        sleep(1); //vod:api 请求频率限制
        //截图
        $this->makeCover();
        sleep(1); //vod:api 请求频率限制
        //转码
        $this->transCode();
        sleep(1); //vod:api 请求频率限制
    }

    public function processVod()
    {
        set_time_limit(600); //queue worker 的timeout 最长就这么长了

        if (!$this->duration) {
            $this->startProcess();
        }
        sleep(5); //5秒后检查

        //15秒内重复检查截图结果
        for ($i = 0; $i < 3; $i++) {
            //同步上传后的信息,获得封面，宽高
            $flag = $this->syncVodProcessResult();
            //有截图就发布
            if ($flag >= 1) {
                $this->publishPost();
                break;
            }
            //这里重复提交截图job是因为几秒的短视频截图不稳定
            $this->makeCover();
            sleep(5);
        }

        //5分钟内尝试30次获取转码结果,目前发现1分钟短视频转码时间不到1分钟...
        for ($i = 0; $i < 30; $i++) {
            //同步上传后的转码结果
            $flag = $this->syncVodProcessResult();
            if ($flag == 2) {
                break;
            }
            sleep(10);
        }
    }

    public function setCover($cover_url)
    {
        $this->cover  = $cover_url;
        $this->status = 1;
        $this->save();
    }

    public function saveCovers($coverUrls)
    {
        $this->setJsonData('covers', $coverUrls);
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
}
