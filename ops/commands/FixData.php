<?php

namespace ops\commands;

use App\Comment;
use App\Image;
use App\Video;
use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($cmd)
    {
        $this->cmd = $cmd;
    }

    public function handle()
    {
        if ($this->cmd->argument('operation') == "tags") {
            return $this->fix_tags();
        }
        if ($this->cmd->argument('operation') == "comments") {
            return $this->fix_tags();
        }
        if ($this->cmd->argument('operation') == "articles") {
            return $this->fix_articles();
        }
        if ($this->cmd->argument('operation') == "images") {
            return $this->fix_images();
        }
        if ($this->cmd->argument('operation') == "videos") {
            return $this->fix_videos();
        }
        if ($this->cmd->argument('operation') == "categories") {
            return $this->fix_categories();
        }
        if ($this->cmd->argument('operation') == "users") {
            return $this->fix_users();
        }
    }

    public function fix_users()
    {
        // 今后，数据写到数据文件里，别堆代码里
    }

    public function fix_tags()
    {

    }

    public function fix_comments()
    {

    }

    public function fix_categories()
    {

    }

    public function fix_videos()
    {
        $this->cmd->info('fix videos ...');
        $qb = Video::orderBy('id');
        $qb->chunk(100, function ($videos) {
            foreach ($videos as $video) {
                //fix duration
                $video_path = starts_with($video->path, 'http') ? $video->path : public_path($video->path);
                if (starts_with($video_path, 'http') || file_exists($video_path)) {
                    $cmd_get_duration = 'ffprobe -i ' . $video_path . ' -show_entries format=duration -v quiet -of csv="p=0" 2>&1';
                    $duration         = `$cmd_get_duration`;
                    $duration         = intval($duration);
                    $video->duration  = $duration;

                    //截取图片
                    $video->cover = '/storage/video/thumbnail_' . $video->id . '.jpg';
                    $this->cmd->info("截取图片:$video->cover => $video_path");
                    $cover = public_path($video->cover);
                    \App\Jobs\videoCapture::dispatch($video_path, $cover, $video->id);
                }

                $video->category_id = 22;
                $video->save();
            }
        });
    }

    public function fix_images()
    {
        $this->cmd->info('fix images ...');
        Image::orderBy('id')->chunk(100, function ($images) {
            foreach ($images as $image) {
                //服务器上图片不在本地的，都设置disk=hxb
                $image->hash = '';
                if (file_exists(public_path($image->path))) {
                    $image->hash = md5_file(public_path($image->path));
                    $image->disk = 'local';
                    $this->cmd->info($image->id . '  ' . $image->extension);
                } else {
                    $image->disk = 'hxb';
                    $this->cmd->comment($image->id . '  ' . $image->extension);
                }
                $image->save();
            }
        });
    }

    public function fix_articles()
    {

    }
}
