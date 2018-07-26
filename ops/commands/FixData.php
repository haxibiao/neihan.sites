<?php
namespace ops\commands;

use App\Comment;
use App\Image;
use App\Video;
use App\Article;
use App\Category;
use App\Collection;
use App\User;
use App\Visit;
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
        if ($this->cmd->argument('operation') == "visits") {
            return $this->fix_visits();
        }
        if ($this->cmd->argument('operation') == "likes") {
            return $this->fix_likes();
        }
        
        if($this->cmd->argument('operation') == "article_comments"){
            return $this->fix_article_comments();
        }
    }

    public function fix_likes(){

    }

    public function fix_notifications()
    {
    
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
        \DB::table('article_category')->where('id',13831)->update([
            'submit'=>'已移除'
        ]);
        $category = Category::find(84);
        $category->decrement('new_requests');
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
        
    }
    public function fix_collections()
    {
        $this->cmd->info('fix collections ...');
        Collection::chunk(100,function($collections){
            foreach ($collections as $conllection) {
                $conllection_id = $conllection->id;
                if(count($conllection->articles()->pluck('article_id')) > 0)
                {
                    $article_id_arr = $conllection->articles()->pluck('article_id');
                    foreach ($article_id_arr as $article_id) {
                        $article = Article::find($article_id);
                        $article->collection_id = $conllection_id;
                        $article->save();
                        $conllection->count_words += $article->count_words;
                        $this->cmd->info('Artcile:'.$article_id.' corresponding collections:'.$conllection_id);
                    }
                    $conllection->count = count($article_id_arr);
                    $conllection->save();
                }
                //
            }
        });
        $this->cmd->info('fix collections success');
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

    public function fix_visits(){
        Visit::chunk(100,function($visits){
            foreach ($visits as $visit) {
                $visit->visited_type = str_plural($visit->visited_type);
                $visit->save(['timestamps'=>false]);
            }
        });
    }
}
