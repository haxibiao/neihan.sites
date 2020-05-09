<?php

namespace App\Traits;

use App\Helpers\FFMpeg\FFMpegUtils;
use App\Video;
use Illuminate\Support\Facades\Storage;

trait MakeCovers
{
    /**
     * 截图，填充视频封面，宽高信息
     * @param string $videoPath
     * @param string $disk
     */
    public function makeCovers(string $videoPath, $disk = 'vod')
    {
//            1. 获取宽高
        $video = $this;
        $videoInfo = $this->getVideoInfo($videoPath);
        $duration = $this->getVideoDuration($videoInfo);
        $width = array_get($videoInfo, 'width');
        $height = array_get($videoInfo, 'height');

//            2.截图，存在本地
        $coverPath = $this->saveCovers($video, $videoPath);

//            3.将本地截图上传
        \Storage::cloud()->put($coverPath, file_get_contents(storage_path('app/public/' . $coverPath)));

//            4.更新视频信息
        $video->timestamps = false;
        $video->disk = $disk;
        $video->status = 1;
        $video->cover = $coverPath;
        $video->duration = $duration;

//            5.填充视频关键信息
        $video->setJsonData('cover', $coverPath);
        $video->setJsonData('width', $width);
        $video->setJsonData('height', $height);

        $video->save();
//            6.释放服务器资源
        if (!is_local_env()) {
            $relativePath = 'video/' . $video->id . '.mp4';
            //删除视频
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
            Storage::disk('public')->delete($video->cover);
        }
    }

    /**
     * @param string $path
     * @return array
     */
    public function getVideoInfo(string $filePath): array
    {
        $videoInfo = FFMpegUtils::getStreamInfo($filePath);
        return $videoInfo;
    }

    /**
     * @param array $videoInfo
     * @return int
     */
    public function getVideoDuration(array $videoInfo): int
    {
        $duration = array_get($videoInfo, 'duration');
        $duration = $duration > 0 ? ceil($duration) : random_int(1, 10);
        return $duration;
    }

    /**
     * @param int $duration
     * @param Video $video
     * @param string $path
     * @return string
     */
    public function saveCovers(Video $video, string $path): string
    {
        $localCoverPathTemp = 'app/public/video/%s';
        $coverName = $video->id . '.jpg';
        $coverPath = sprintf(storage_path($localCoverPathTemp), $coverName);

        FFMpegUtils::saveCover($path, 1, $coverPath);

        $result = 'video/' . $coverName;
        return $result;
    }
}
