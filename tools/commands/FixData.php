<?php

namespace tools\commands;

use App\Article;
use App\Category;
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
    public function __construct($commander)
    {
        $this->commander = $commander;
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
       
    }

    public function fix_images()
    {
        $this->commander->info('fix images ...');
        Image::orderBy('id')->chunk(100, function ($images) {
            foreach ($images as $image) {
                //服务器上图片不在本地的，都设置disk=hxb
                $image->hash = '';
                if (file_exists(public_path($image->path))) {
                    $image->hash = md5_file(public_path($image->path));
                    $image->disk = 'local';
                    $this->commander->info($image->id . '  ' . $image->extension);
                } else {
                    $image->disk = 'hxb';
                    $this->commander->comment($image->id . '  ' . $image->extension);
                }
                $image->save();
            }
        });
    }

    public function fix_articles()
    {
        
    }

    public function fix_article_image($article)
    {
        
    }

    public function fix_traffic()
    {
        
    }
}
