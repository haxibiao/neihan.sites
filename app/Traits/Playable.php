<?php

namespace App\Traits;

trait Playable
{
    /**
     * @Desc     获取视频的封面图
     * @Author   czg
     * @DateTime 2018-06-27
     * @return   [type]     [description]
     */
    public function cover()
    {
        $cover_url = "";
        if (!empty($this->image_url) && !starts_with($this->image_url, 'http')) {
            $cover_url = file_exists(public_path($this->image_url)) ? url($this->image_url) : env('APP_URL') . $this->image_url;
        } else {
            //尝试同步关联视频的远程封面地址
            if ($video = $this->video) {
                if (!empty($video->cover)) {
                    $this->image_url = $video->cover;
                    $this->save();
                    $cover_url = $video->cover;
                }
            }
        }

        $justChanged = $this->updated_at && $this->updated_at->addMinutes(10) > now();
        if ($justChanged) {
            $cover_url = $cover_url . '?t=' . time();
        }
        return $cover_url;
    }
    /**
     * @Desc     获取视频所有的截图 全地址
     * @Author   czg
     * @DateTime 2018-06-27
     * @return   [type]     [description]
     */
    public function covers()
    {
        $covers = [];
        for ($i = 1; $i <= 8; $i++) {
            $cover = $this->image_url . "." . $i . ".jpg";
            if (file_exists(public_path($cover))) {
                $covers[] = url($cover);
            }
        }
        return $covers;
    }
    /**
     * @Desc     获取视频播放地址,视频地址有CDN以及网站相对地址,如果是相对地址则变化成全地址
     * @Author   czg
     * @DateTime 2018-06-27
     * @return   [type]     [description]
     */
    public function video_source()
    {
        if (!$this->video) {
            return '';
        }
        return $this->video->url();
    }
    /**
     * @Desc     关联用户的详细信息
     * @Author   czg
     * @DateTime 2018-06-27
     * @return   [type]     [description]
     */
    public function video()
    {
        return $this->belongsTo('App\Video');
    }
}
