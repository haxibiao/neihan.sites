<?php

namespace App\Traits;

use Haxibiao\Helpers\QcloudUtils;

//过期的一些VOD 函数
trait VideoRepoVod
{

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
                //文章发布
                $article->status = 1;
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

}
