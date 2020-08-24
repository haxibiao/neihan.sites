<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait ProductRepo
{
    //nova 视频上传
    public function saveVideoFile(UploadedFile $file)
    {
        $hash = md5_file($file->getRealPath());
        $video = \App\Video::firstOrNew([
            'hash' => $hash,
        ]);
        //        秒传
        if (isset($video->id)) {
            return $video->id;
        }

        $uploadSuccess = $video->saveFile($file);
        throw_if(!$uploadSuccess, Exception::class, '视频上传失败，请联系管理员小哥');
        return $video->id;
    }

    public function saveDownloadImage($file)
    {
        if ($file) {
            $task_logo = 'product/product' . $this->id . '_' . time() . '.png';
            $cosDisk = \Storage::cloud();
            $cosDisk->put($task_logo, \file_get_contents($file->path()));

            return $task_logo;
        }
    }
}
