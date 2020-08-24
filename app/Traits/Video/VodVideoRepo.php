<?php

namespace App\Traits;

use Haxibiao\Helpers\VodUtils;
use App\User;
use App\Video;

trait VodVideoRepo
{
    // 通过 VOD file_id 保存信息至 videos table
    public static function saveByVodFileId($fileId, User $user)
    {
        VodUtils::makeCoverAndSnapshots($fileId);
        $vodVideoInfo = VodUtils::getVideoInfo($fileId);
        return self::saveVodFile($user, $fileId, $vodVideoInfo);
    }

    // 根据 VODUtils 返回的信息创建 Video
    public static function saveVodFile(User $user, $fileId, array $videoFileInfo)
    {
        $url      = data_get($videoFileInfo, 'basicInfo.sourceVideoUrl');
        $cover    = data_get($videoFileInfo, 'basicInfo.coverUrl');
        $duration = data_get($videoFileInfo, 'basicInfo.duration');
        $height   = data_get($videoFileInfo, 'metaData.height');
        $width    = data_get($videoFileInfo, 'metaData.width');
        $hash     = md5_file($url);

        $video = new Video();

        $video->user_id      = $user->id;
        $video->disk         = 'vod';
        $video->hash         = $hash;
        $video->qcvod_fileid = $fileId;
        $video->path         = $url;
        $video->width        = $width;
        $video->height       = $height;
        $video->duration     = $duration;
        $video->cover        = $cover;
        $video->json         = json_encode($videoFileInfo);
        $video->save();

        return $video;
    }
}
