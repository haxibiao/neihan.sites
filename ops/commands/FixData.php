<?php

namespace ops\commands;

use App\Comment;
use App\Image;
use App\Video;
use App\Article;
use App\Category;
use App\Collection;
use Symfony\Component\DomCrawler\Crawler;
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
        if ($this->cmd->argument('operation') == "notifications") {
            return $this->fix_notifications();
        }
    }

    public function fix_notifications(){
        $this->cmd->info('fix notifications ...');
        //删除通知表中投稿请求类型的通知类型
        \DB::table('notifications')->where('type', 'App\Notifications\CategoryRequested')->delete();
        //修复之前的脏数据
        \App\Category::where('id', 76)->update([
            'new_requests' => 0,
        ]);
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
        $this->cmd->info('delete empty videos ...');
        $qb = Video::orderBy('id')->whereNull('path');
        $qb->chunk(100, function ($videos) {
             foreach ($videos as $video) {
                $video->delete();
            }
        });
        $this->cmd->info('fix videos ...');
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
        $this->cmd->info('fix articles ...');
        //修复非爬虫文章文章内容中图片的问题
        $qb = Article::where('id', '=', '1263')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $body_html = $article->body;
                if(empty($body_html)){
                    continue;
                }
                //匹配正文中所有的图片路径
                $pattern = "/<img.*?src=['|\"](.*?)['|\"].*?[\/]?>/iu";
                preg_match_all($pattern, $body_html, $matches);
                $img_urls = end($matches);
                
                foreach ($img_urls as $img_url) {
                    $new_img_url = '';
                    if( starts_with($img_url, 'https://ainicheng.com/') ){

                        continue;
                    } else if ( starts_with($img_url, '/storage/') ){
                        //网站相对地址
                        $new_img_url = url($img_url);
                    } else {
                        //下载图片
                        $image          = new Image();
                        $image->user_id = $article->user_id;
                        $image->save(); 

                        $extension       = substr($img_url,strripos($img_url,'.'));
                        $image->extension = $extension;
                        $filename        = $image->id . $extension;
                        $image->path      = '/storage/img/' . $filename; 
                        $local_dir = public_path('/storage/img/');
                        if (!is_dir($local_dir)) {
                            mkdir($local_dir, 0777, 1);
                        }

                        $img = \ImageMaker::make($img_url);
                        if ($extension != 'gif') {
                            $big_width = $img->width() > 900 ? 900 : $img->width();
                            $img->resize($big_width, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            //save big
                            $img->save(public_path($image->path));

                            $image->width  = $img->width();
                            $image->height = $img->height();
                        } else {
                            $file->move($local_dir, $filename);
                        }

                        //save top
                        if ($extension != 'gif') {
                            if ($img->width() >= 760) {
                                $img->crop(760, 327);
                                $image->path_top = '/storage/img/' . $image->id . '.top.' . $extension;
                                $img->save(public_path($image->path_top));
                            }
                        } else {
                            if ($img->width() >= 760) {
                                $image->path_top = $image->path;
                            }
                        }
                        //save small
                        if ($img->width() / $img->height() < 1.5) {
                            $img->resize(300, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } else {
                            $img->resize(null, 240, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $img->crop(300, 240);
                        $image->disk = "local";
                        $img->save(public_path($image->path_small()));
                        $image->save();

                        $new_img_url = $image->url();
                    }
                    $article->body = str_replace($img_url, $new_img_url, $body_html);
                    $article->save();
                    var_dump('http://l.ainicheng.com/article/' . $article->id);
                }
            }
        });

        //维护主分类与文章的多对多关系
        // $this->cmd->info('fix articles ...');
        // Article::orderBy('id')->chunk(100, function ($articles) {
        //     foreach ($articles as $article) {
        //         if(empty($article->category_id)){
        //             continue;
        //         }
        //         $article->categories()
        //             ->syncWithoutDetaching(
        //                 [$article->category_id=>['submit' => '已收录']]
        //             );
        //         $this->cmd->info($article->title);
        //     }
        // });
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
