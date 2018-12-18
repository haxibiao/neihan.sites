<?php

namespace App;

use App\Article;
use App\Helpers\QcloudUtils;
use App\Model;
use App\Traits\Jsonable;
use App\Traits\UserRelated;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, Jsonable, UserRelated;

    protected $fillable = [
        'title',
        'user_id',
        'path',
        'duration',
        'hash',
        'adstime',
        'qcvod_fileid',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function article()
    {
        return $this->hasOne(\App\Article::class);
    }

    public static function boot()
    {
        parent::boot();
        //同步article status
        static::updated(function ($model) {
            if ($article = $model->article) {
                $article->status = $model->status;
            }
        });
    }

    public function takeSnapshot($force = false, $flag = true)
    {
        \App\Jobs\TakeScreenshots::dispatch($this, $force, $flag);
    }

    public function getPath()
    {
        //TODO: save this->extension, 但是目前基本都是mp4格式
        $extension = 'mp4';
        return '/storage/video/' . $this->id . '.' . $extension;
    }

    public function url()
    {
        if (starts_with($this->path, 'http')) {
            return $this->path;
        }
        return file_exists(public_path($this->path)) ? url($this->path) : env('APP_URL') . $this->path;
    }

    /**
     * @Desc     保存视频文件
     * @Author   czg
     * @DateTime 2018-07-11
     * @param    [type]     $file [description]
     * @param    [type]     $flag 是否改变文章的状态
     * @return   [type]           [description]
     */
    public function saveFile($file, $flag = true)
    {
        if ($file) {
            $this->user_id = getUserId();
            $this->save();

            //保存视频地址
            $hash       = md5_file($file->path());
            $extension  = $file->getClientOriginalExtension();
            $filename   = $this->id . '.' . $extension;
            $path       = '/storage/video/' . $filename;
            $this->path = $path;
            $this->hash = $hash;
            $file->move(public_path('/storage/video/'), $filename);
            $this->save();

            //截取图片
            $this->takeSnapshot(true, $flag);
            return true;
        }
        return false;
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
            if (!empty($res['snapshotByTimeOffsetInfo']) &&
                !empty($res['snapshotByTimeOffsetInfo']['snapshotByTimeOffsetList'])) {
                $covers = [];
                foreach ($res['snapshotByTimeOffsetInfo']['snapshotByTimeOffsetList'] as $snapInfo) {
                    if ($snapInfo['definition'] == 10) {
                        foreach ($snapInfo['picInfoList'] as $urlArr) {
                            $url      = $urlArr["url"];
                            $covers[] = get_secure_url($url);
                        }
                    }
                }
                $this->setJsonData('covers', $covers);
            }
            if (!empty($res['transcodeInfo']) && !empty($res['transcodeInfo']['transcodeList'])) {
                $video_urls = [];
                foreach ($res['transcodeInfo']['transcodeList'] as $codeInfo) {
                    //同步其他码率的url
                    if (!empty($codeInfo['templateName'])) {
                        if (str_contains($codeInfo['templateName'], '流畅')) {
                            $video_urls['流畅'] = get_secure_url($codeInfo['url']);
                        }
                        if (str_contains($codeInfo['templateName'], '标清')) {
                            $video_urls['标清'] = get_secure_url($codeInfo['url']);
                        }
                        if (str_contains($codeInfo['templateName'], '高清')) {
                            $video_urls['高清'] = get_secure_url($codeInfo['url']);
                        }
                        if (str_contains($codeInfo['templateName'], '全高清')) {
                            $video_urls['全高清'] = get_secure_url($codeInfo['url']);
                            //默认播放url
                            $this->path = get_secure_url($codeInfo['url']);
                            $flag       = 2;
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
        QcloudUtils::makeCoverAndSnapshots($this->qcvod_fileid, $this->duration);
    }

    public function transCode()
    {
        QcloudUtils::convertVodFile($this->qcvod_fileid, $this->duration);
    }

    public function processVod()
    {
        set_time_limit(600); //queue:work 的timeout 现在是600秒，需要更长要去ops下修改 worker conf..

        //截图
        $this->makeCover();
        //转码
        $this->transCode();

        sleep(10); //10秒后检查

        //30秒内重复检查截图结果
        for ($i = 0; $i < 3; $i++) {
            //同步上传后的信息,获得封面，宽高，duration等
            $flag = $this->syncVodProcessResult();
            //有截图就发布
            if ($flag >= 1) {
                $this->publishPost();
                break;
            }
            //这里重复提交截图job是因为几秒的短视频截图不稳定
            $this->makeCover();
            sleep(10);
        }

        //10分钟内尝试10次获取转码结果,目前发现1分钟短视频转码时间不到1分钟...
        for ($i = 0; $i < 10; $i++) {
            //同步上传后的转码结果
            $flag = $this->syncVodProcessResult();
            if ($flag == 2) {
                break;
            }
            sleep(60);
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
            $action = Action::updateOrCreate([
                'user_id'         => $this->user_id,
                'actionable_type' => 'videos',
                'actionable_id'   => $this->id,
            ]);
        }
    }
}
