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
        
        if($this->cmd->argument('operation') == "article_comments"){
            return $this->fix_article_comments();
        }
    }

    public function fix_notifications()
    {
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
        //删除文章体中 视频部分
        Article::where('id','<',1000)->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $body_html = $article->body;
                $start = strpos( $body_html,'<div>新手教程：' );
                if( !$start ){
                    continue;
                }
                $end   = strpos( $body_html,'</div>', $start); 
                $rep_str = substr($body_html, $start, $end-$start+6);
                $article->body = str_replace($rep_str, '', $body_html);
                $article->timestamps = false;
                $article->save();
                $this->cmd->info('删除文章体中视频>>>'.$article->id.'<<<成功');
            }
        });
        //维护文章与图片的关系,非爬虫文章
        Article::where('source_url', '=', '0')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $body_html = $article->body;
                $img_from_image_urls = \ImageUtils::getImageUrlFromHtml($body_html);
                $paths = [];
                foreach ($img_from_image_urls as $image_url) {
                    //排除base64图片
                    if( strlen($image_url)<100 && str_contains($image_url,'ainicheng.com') ){
                        $paths[] = substr($image_url, strpos($image_url,"/storage"));
                    }
                }
                $img_ids = Image::whereIn('path',$paths)->pluck('id');
                $article->images()->syncWithoutDetaching($img_ids);
            }
        });
        //给文章体中部分Img标签添加宽高属性
        Article::where('source_url', '=', '0')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $this->cmd->error('正在替换文章:'. $article->id);
                $body_html = $article->body;
                $pattern = "/<img.*?src=['|\"](.*?)['|\"].*?[\/]?>/iu";
                preg_match_all($pattern, $body_html, $matches);

                $paths = [];
                foreach ($matches[0] as $img) {
                    $image_url = \ImageUtils::getImageUrlFromHtml($img)[0];
                    //防止img中src属性为空
                    if( empty($image_url) ){
                        $this->cmd->notice($article->id.'图为空' . $image_url); 
                        continue;
                    }
                    //判断是否有width与height属性，同时跳过base64编码图片
                    if( strlen($image_url)<100 && (!str_contains( $img, [ 'width=','height='] )) ){
                        try {
                            list($width,$height,$type,$attr)=getimagesize($image_url);
                            $body_html = str_replace(
                                $image_url . '"' , 
                                $image_url . '" ' . 'width="'.$width.'" ' . 'height="'.$height.'" ' , 
                                $body_html
                            );
                        } catch (\Exception $e) {
                            $this->cmd->error($article->id.'获取宽高属性失败' . $image_url); 
                        }
                    }
                }
                $article->body = $body_html;
                $article->timestamps = false;
                $article->save();
            }
        });
    }
    public function fix_collections()
    {
        
    }

    public function fix_article_comments()
    {
        //修复Article评论数据
        $this->cmd->info('fix article comments...');
        Comment::whereNull('comment_id','and',true)->chunk(100,function($comments){
            foreach ($comments as $comment) {
                if(empty(Comment::find($comment->comment_id))){
                    $article_id = $comment->commentable_id;
                    $comment->delete();
                    $this->cmd->info('文章： https://l.ainicheng.com/article/'.$article_id);
                }
            }
        });
        $this->cmd->info('fix articles count_comments...');
        //修复Article评论统计数据
        Article::chunk(100,function($articles){
            foreach ($articles as $article) {
                $article->count_replies  = $article->comments()->count();
                $article->count_comments = $article->comments()->max('lou');
                $article->save();
            }
        });
        $this->cmd->info('fix success');
    }
}
