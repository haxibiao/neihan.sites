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
        
        if($this->cmd->argument('operation') == "article_comments"){
            return $this->fix_article_comments();
        }
    }

    public function fix_notifications()
    {
        //修复老视频的跳转问题
        $this->cmd->info('fix notifications ...');
        \DB::table('notifications')->orderBy('id')->where('type','App\Notifications\ArticleCommented')->chunk(100,function($notifications){
            foreach ($notifications as $notification) {
                $data = $notification->data;
                $json = json_decode($data);
                //提取Body中@人的消息
                $commentBody        = $json->comment;
                $filtered_users;//需要@的人
                $pattern = "/@([^\s|\/|:|@]+)/i";
                preg_match_all($pattern, $commentBody, $matches);
                $atlist_tmp = end($matches);
                if( !empty($atlist_tmp) ){
                    $mentioned_user_name    = array_values($atlist_tmp);
                    $at_users   = User::whereIn('name',$mentioned_user_name)->get();
                    if( $at_users->isNotEmpty() ){ 
                        //格式化消息通知的内容
                        $user_names = $at_users->pluck('name')->toArray();
                        $material_name = array_map(function($name){
                            return '@' . $name;
                        }, $user_names);
                        //拼接消息内容
                        $format_at_users = [];
                        foreach ($at_users as $user) {
                            $format_at_users[] = $user->at_link();
                        }
                        $commentBody = str_replace( 
                            $material_name, 
                            $format_at_users, 
                            $commentBody
                        );
                        //更新评论的内容，替换了超链接
                        $json->comment  = $commentBody;
                        $json->body     = $commentBody;
                    }
                }
                //评论的人
                $authorizer = User::find($json->user_id);
                //评论
                $article    = Article::find($json->article_id);
                //如果评论的文章或视频不存在
                $msg = '';
                $json->lou      = Comment::find($json->comment_id)->lou;
                if(empty($article)){
                    $msg = '<a href="/user/' . $json->user_id . '">' . $json->user_name . '</a> 评论了你的文章 <a href="/artice/'.$json->article_id.'">《' . $json->article_title . '》</a>';
                } else {
                    //区分评论的"文章"类型
                    $type = '文章';
                    if($article->type == 'video'){
                        $type = '视频';
                    } else if( $article->type == 'post' ){
                        $type = '动态';
                    }

                    $msg = $authorizer->link() . ' 评论了你的' . $type . $article->link();
                    $json->title    = $msg;
                    $json->url      = $article->content_url();
                    \DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['data' => json_encode($json)]);
                }
                
            }
        });
    }

    public function fix_users()
    {
        //修复网站用户名重复问题
        $this->cmd->info('fix users ...');
        User::chunk(100,function($users){
            foreach ($users as $user) {
                $user_name = $user->name;
                $has_same  = User::whereName($user_name)->count()>=2;
                if(!$has_same){
                    continue;
                }
                $user->name = $user->name.'_'.str_random(5);;
                $user->save(['timestamps'=>false]);
            }
        });
    }

    public function fix_tags()
    {
    }

    public function fix_comments()
    {
 
    }

    public function fix_categories()
    {
         $category = Category::find(22);
         $category->status = 1;
         $category->save();
         $this->cmd->info("$category->name fix success");
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
        $category = Category::where('name', 'dota2')->first();
        $articles = $category->articles;
        foreach ($articles as $article) {
            $body = $article->body;
            $is_modify = count( \ImageUtils::getImageUrlFromHtml($body) ) >= 2;
            if( !$is_modify ){
                $article->status = -1;
                $article->save(['timestamps'=>false]);
            }
        }
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
