<?php


    namespace App\Helpers\FFMpeg;


    class FFMpegUtils
    {
        /**
         * 获取视频信息
         * @param $filePath
         * @return array
         */
        public static function getStreamInfo($filePath){
            $ffprobe = app('ffprobe');
            $stream  = $ffprobe->streams($filePath)->videos()->first();
            return $stream ? $stream->all() : [];
        }


        /**
         * 截取视频图片
         * @param $filePath
         * @param $second
         * @param $fileSavePath
         * @return string
         */
        public static function saveCover($filePath,$second,$fileSavePath){
            $ffmpeg = app('ffmpeg');
            $video     = $ffmpeg->open($filePath);
            $frame     = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($second)); //提取第几秒的图像
            $frame->save($fileSavePath);

            return $fileSavePath;
        }
    }