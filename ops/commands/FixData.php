<?php

namespace ops\commands;

use App\Comment;
use App\Image;
use App\Video;
use App\Article;
use App\Category;
use App\Collection;
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
        if ($this->cmd->argument('operation') == "collections") {
            return $this->fix_collections();
        }
    }

    public function fix_users()
    {

        $user = \App\User::where('id', 295)
            ->update([
                'is_editor'=>1,
                'is_seoer' =>1
            ]);
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
        //重新统计分类下已收录文章数
        $this->cmd->info('fix categories ...');
        Category::orderBy('id')->chunk(100, function ($categories) {
            foreach ($categories as $category) {
                $category->count = $category->publishedArticles()->count();
                $category->save();
            }
        });
    } 

    public function fix_videos()
    {
        $this->cmd->info('fix videos ...');
        $qb = Video::orderBy('id')->whereNull('path');
        $qb->chunk(100, function ($videos) {
             foreach ($videos as $video) {
                $video->delete();
            }
        });
        /*$this->cmd->info('fix videos ...');
        $qb = Video::orderBy('id')->where('status', 0);
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
                    $video->takeSnapshot();
                    $this->cmd->info("截取图片:$video->cover => $video->path");
                }

                $video->category_id = 22;
                $video->save();
            }
        });*/
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
        //维护主分类与文章的多对多关系
        $this->cmd->info('fix articles ...');
        Article::orderBy('id')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                if(empty($article->category_id)){
                    continue;
                }
                $article->categories()
                    ->syncWithoutDetaching(
                        [$article->category_id=>['submit' => '已收录']]
                    );
                $this->cmd->info($article->title);
            }
        });
    }
    public function fix_collections()
    {
        $this->cmd->info('fix collections ...');
        Collection::orderBy('id')->where('status',1)->chunk(100, function ($collections) {
            foreach ($collections as $collection) {
                $articles = $collection->publishedArticles()->get();
                $total_words = 0;
                foreach ($articles as $article) {
                    $total_words += $article->count_words; 
                }
                $collection->count_words = $total_words;
                $collection->count = count($articles);
                $collection->save();
            }
        });
    }
}
