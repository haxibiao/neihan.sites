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
use App\Action;
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
        if($this->cmd->argument('operation') == "actions"){
            return $this->fix_actions();
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
        $this->cmd->info('fix count_videos ...');
        foreach(Category::all() as $category) {
            $category->count_videos = $category->videoPosts()->count();
            $category->save();
        }
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
        Article::where('source_url', 0)->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $fix   = false;
                $title = $article->title;
                $preg  = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
                preg_match_all($preg, $article->body, $matches);
                $image_urls = $matches[1];
                if (!empty($image_urls)) {
                    for ($i = 0; $i < count($image_urls); $i++) {
                        $image_url = $image_urls[$i];
                        if (str_contains($image_url, $title)) {
                            $image_tag = $matches[0][$i];
                            $preg      = "/<img.+height=\"(\d*?)\">/i";
                            preg_match_all($preg, $image_tag, $image_height);
                            $image_height = $image_height[1][0];
                            $preg         = "/<img.+width=\"(\d*?)\".+>/i";
                            preg_match_all($preg, $image_tag, $image_width);
                            $image_width  = $image_width[1][0];
                            $articlImages = $article->images()->get();
                            $image_path   = '';
                            foreach ($articlImages as $image) {
                                if ($image->width == $image_width && $image->height == $image_height) {
                                    $image_path = $image->url_prod();
                                }
                            }
                            $new_Image     = str_replace($image_url, $image_path, $image_tag);
                            $body          = $article->body;
                            $article->body = str_replace($image_tag, $new_Image, $body);
                            $article->save();
                            $fix = true;
                        }
                    }
                    if ($fix) {
                        $this->cmd->info('Article Id: ' . $article->id . ' fix sucess');
                    }
                }
            }
        });
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

    public function fix_actions()
    {
        $this->cmd->info('fix article action');
        Article::where('status','1')->chunk(100,function($articles){
            foreach ($articles as $article) {
                $article_id = $article->id;
                $acion_article = Action::where('actionable_type','articles')
                ->where('actionable_id',$article_id)->get();
                if(!$acion_article->count()){
                    $action = Action::updateOrCreate([
                        'user_id'         => $article->user_id,
                        'actionable_type' => 'articles',
                        'actionable_id'   => $article->id,
                        'created_at'=>$article->created_at,
                        'updated_at'=>$article->updated_at
                    ]);
                    $this->cmd->info('fix Article Id:'.$article->id.' fix success');
                }
            }
        });
        $this->cmd->info('fix article action success');
    }
}
