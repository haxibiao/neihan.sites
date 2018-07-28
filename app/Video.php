<?php

namespace App;

use App\Article;
use App\Helpers\QcloudUtils;
use App\Model;
use App\Traits\Jsonable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, Jsonable;

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
        //简单顾虑，视频地址确实是上传到了vod的
        if (str_contains($this->path, 'vod')) {
            $res = QcloudUtils::getVideoInfo($this->qcvod_fileid);
            if (!empty($res['basicInfo']) && !empty($res['basicInfo']['coverUrl'])) {
                $this->cover = $res['basicInfo']['coverUrl'];
            }
            if (!empty($res['sampleSnapshotInfo']) &&
                !empty($res['sampleSnapshotInfo']['sampleSnapshotList'])) {
                $covers = [];
                foreach ($res['sampleSnapshotInfo']['sampleSnapshotList'] as $snapInfo) {
                    // if ($snapInfo['definition'] == 10)
                    {
                        foreach ($snapInfo['imageUrls'] as $url) {
                            $covers[] = $url;
                        }
                    }
                }
                $this->setJsonData('covers', $covers);
            }
            if (!empty($res['transcodeInfo']) && !empty($res['transcodeInfo']['transcodeList'])) {
                $video_urls = [];
                foreach ($res['transcodeInfo']['transcodeList'] as $codeInfo) {
                    if (!empty($codeInfo['templateName'])) {
                        if (str_contains($codeInfo['templateName'], '流畅')) {
                            $video_urls['流畅'] = $codeInfo['url'];
                        }
                        if (str_contains($codeInfo['templateName'], '标清')) {
                            $video_urls['标清'] = $codeInfo['url'];
                        }
                        if (str_contains($codeInfo['templateName'], '高清')) {
                            $video_urls['高清'] = $codeInfo['url'];
                        }
                        if (str_contains($codeInfo['templateName'], '全高清')) {
                            $video_urls['全高清'] = $codeInfo['url'];
                        }
                    }
                }
                $this->setJsonData('video_urls', $video_urls);
            }
            //同步到封面，就发布成功
            if (!empty($this->cover)) {
                $this->status = 1;
                $this->save();
            }
        }
    }

    public function setCover($cover_url)
    {
        $this->cover = $cover_url;
        $this->status = 1;
        $this->save();
    }

    public function saveCovers($coverUrls)
    {
        $this->setJsonData('covers', $coverUrls);
        $this->save();
    }
}
