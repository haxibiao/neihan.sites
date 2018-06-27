<?php

namespace ops\commands;

use App\Comment;
use App\Image;
use App\Video;
use App\Article;
use App\Category;
use App\Collection;
use App\User;
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
        //fix 用户头像
        $this->cmd->info('fix usedr avatar ...');
        User::where('avatar','like','%images/avatar.jpg')->chunk(100,function($users){
            foreach ($users as $user) {
                $user->avatar = '/images/avatar-'.rand(1, 15).'.jpg';
                $user->save();
            }
        });
        $this->cmd->info('success');
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
        //base64 fail 1370 12520 11298 12522
        //webp 12520
        $this->cmd->info('fix articles ...');
        //修复非爬虫文章文章内容中图片的问题(图片的宽高属性单独处理)
        Article::where('source_url', '=', '0')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $body_html = $article->body;
                //body为空的情况
                if(empty($body_html)){ 
                    continue;
                }
                //匹配正文中所有的图片路径
                $img_from_image_urls = \ImageUtils::getImageUrlFromHtml($body_html);
                
                foreach ($img_from_image_urls as $img_url) {
                    $new_img_url= '';
                    $old_img_url = $img_url;
                    //我们域名下的图片，直接跳过
                    if( str_contains($img_url, [
                            'ainicheng.com' , 
                            'youjianqi.com' ,
                            'qunyige.com'   ,
                            'haxibiao.com'
                        ]) )
                    {
                        continue;
                    //base64图片
                    } else if( starts_with($img_url, 'data:image') ) {
                        $image            = new Image();
                        $image->user_id = $article->user_id;
                        $image->title = $article ? $article->title : '';
                        $image->save();
                        $img_data  = \ImageUtils::getBase64ImgStream($img_url);
                        //图片扩展名
                        $extension = \StringUtils::getNeedBetween($img_url, 'data:image/', ';');
                        $image->extension = $extension;
                        //图片相对路径
                        $filename        = $image->id .'.'. $extension;
                        $image->path      = '/storage/img/' . $filename;

                        $local_dir = public_path('/storage/img/');
                        if (!is_dir($local_dir)) {
                            mkdir($local_dir, 0777, 1);
                        }
                        try { 
                            $img         = \ImageMaker::make($img_data);
                            //保存大图
                            if ($extension != 'gif') {
                                $big_width = $img->width() > 900 ? 900 : $img->width();
                                $img->resize($big_width, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                $img->save(public_path($image->path));

                                $image->width  = $img->width();
                                $image->height = $img->height();
                            } else {
                                file_put_contents($local_dir . $filename ,$img_data);
                            }

                            //保存轮播图
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
                            //保存小图
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
                            //$img_width  = $image->width;
                            //$img_height = $image->height;
                        } catch (\Exception $e) {
                            file_put_contents($local_dir . $filename ,$img_data);
                            var_dump('base64网络图片下载失败-文章链接:' . 'http://ainicheng.com/article/' . $article->id);
                            continue;
                        } 
                        $image->save();
                        $new_img_url = $image->url();
                    } else if (str_contains($img_url, 'video/thumbnail_')) {      
                        var_dump('视图截图-文章链接:' . 'http://ainicheng.com/article/' . $article->id);
                        continue;
                    //网站相对地址     
                    } else if (starts_with($img_url, '/storage/')) {
                        $new_img_url = url($img_url);
                    //其他站点下的图片     
                    } else if ( starts_with($img_url, 'http') ){
                        //http://p2.qhimgs4.com/t01cf38507c3b62d50d.webp
                        try {
                            //规整url
                            $img_url = str_replace('amp;', '', $img_url);
                            //新浪图片服务器网址会发生301重定向
                            if( str_contains($img_url,['r.sinaimg.cn'])){
                                $img_url = 'https:' . \HttpUtils::getReal($img_url); 
                            }

                            $image            = Image::firstOrNew([
                                'source_url' => $img_url,
                            ]);
                            $image->user_id = $article->user_id;
                            $image->title = $article ? $article->title : '';
                            $image->save();

                            $img_data = file_get_contents($img_url);       

                            if (!empty($img_data)) {
                                //图片扩展名
                                if(str_contains($img_url,'cms-bucket.nosdn.127.net')){
                                    $extension = 'jpeg';
                                } else if(str_contains($img_url,['am-a.akamaihd.net','img03.sogoucdn.com'])){
                                    $extension = 'png';
                                } else {
                                    $extension= pathinfo($img_url, PATHINFO_EXTENSION);
                                }

                                $image->extension = $extension;
                                //图片相对路径
                                $filename        = $image->id .'.'. $extension;
                                $image->path      = '/storage/img/' . $filename;

                                $local_dir = public_path('/storage/img/');
                                if (!is_dir($local_dir)) {
                                    mkdir($local_dir, 0777, 1);
                                }

                                $img         = \ImageMaker::make($img_data);
                                //保存大图
                                if ($extension != 'gif') {
                                    $big_width = $img->width() > 900 ? 900 : $img->width();
                                    $img->resize($big_width, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $img->save(public_path($image->path));

                                    $image->width  = $img->width();
                                    $image->height = $img->height();
                                } else {
                                    file_put_contents($local_dir . $filename ,$img_data);
                                } 

                                //保存轮播图
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
                                //保存小图
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
                        } catch (\Exception $e) {
                            var_dump('网络图片下载失败:' . $old_img_url);
                            continue;
                        } 
                    } else {
                        var_dump('没有命中:' . $old_img_url);
                    }
                    $body_html = str_replace(
                        $old_img_url , 
                        $new_img_url , 
                        $body_html
                    );
                }
                $article->body = $body_html;
                $article->save();
               
                var_dump('http://ainicheng.com/article/' . $article->id);
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
