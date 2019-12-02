<?php

namespace App\Traits;

use App\Jobs\MakeVideoCovers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait VideoRepo
{
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
        MakeVideoCovers::dispatch($this);

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

    public function makeCovers()
    {
        $video = $this;

        /* ===视频时长=== */
        $videoPath        = storage_path('app/public/video/' . $this->id . '.mp4');
        $cmd_get_duration = 'ffprobe -i ' . $videoPath . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
        $duration         = `$cmd_get_duration`;

        //等比例截取1张图片 20%
        $timeCodes = [
            bcmul(sprintf('%.2f', $duration), 0.2, 2),
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
            info($localCoverPath);
            $cmd = "ffmpeg -ss $second -i $videoPath -an -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $localCoverPath 2>&1";

            $exit_code = exec($cmd);

            $coverPath = 'video/' . $coverName . '.jpg';

            Storage::cloud()->put($coverPath, file_get_contents($localCoverPath));
            $covers[] = $coverPath;

            // 为了解决 FILESYSTEM_CLOUD = public 时，访问不到本地的图片，这里不存 storage
            $cosCovers[] = 'video/' . $coverName . '.jpg';
        }
        if (count($covers)) {
            $video->timestamps = false;
            $video->disk       = 'cos';
            $video->status     = 1; //1代表文章可用，0代表草稿
            $video->cover      = $covers[0]; //TODO: 数据库还是存path，不存cos全地址，需要fixData统一

            $this->setJsonData('covers', $cosCovers);
            $this->setJsonData('cover', $cosCovers[0]);

            $this->saveWidthHeight(\Storage::cloud()->url($cosCovers[0]));

            $video->save();

            //更新文章的状态
            $article = \App\Article::firstOrNew([
                'video_id' => $video->id,
            ]);

            $article->user_id    = $this->user_id;
            $article->cover_path = $cosCovers[0];

            if (!$article->type) {
                $article->type        = 'video';
                $article->description = $this->title;
                $article->body        = $this->title;
            }
            $article->save();

            //释放服务器资源
            if (!is_local_env()) {
                Storage::disk('public')->delete($video->path);

                foreach ($cosCovers as $cover) {
                    Storage::disk('public')->delete($cover);
                }

            }
        }
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
