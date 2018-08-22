<?php
namespace ops\commands;

use App\Action;
use App\Article;
use App\Category;
use App\Collection;
use App\Comment;
use App\Image;
use App\Visit;
use App\User;
use Illuminate\Support\Facades\DB;
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
        $table = $this->cmd->argument('table');
        FixData::$table($this->cmd);
    }

    public static function categories($cmd)
    {
        $cmd->info('fix count_videos ...');
        foreach (Category::all() as $category) {
            $category->count_videos = $category->videoPosts()->count();
            $category->save();
        }
    }

    public static function images($cmd)
    {
        $cmd->info('fix images ...');
        Image::orderBy('id')->chunk(100, function ($images) use ($cmd) {
            foreach ($images as $image) {
                //服务器上图片不在本地的，都设置disk=hxb
                $image->hash = '';
                if (file_exists(public_path($image->path))) {
                    $image->hash = md5_file(public_path($image->path));
                    $image->disk = 'local';
                    $cmd->info($image->id . '  ' . $image->extension);
                } else {
                    $image->disk = 'hxb';
                    $cmd->comment($image->id . '  ' . $image->extension);
                }
                $image->save();
            }
        });
    }

    public static function articles($cmd)
    {
        $cmd->info('fix article_category ...');
        $articles = DB::table('article_category')->select('category_id','article_id')
        ->whereSubmit('已收录')->groupBy(['category_id','article_id'])->havingRaw('count(*) > 1')->get();
        foreach ($articles as $article) {
            $category_id = $article->category_id;
            $article_id = $article->article_id;
            $article_category = DB::table('article_category')->
                where('category_id',$category_id)->where('article_id',$article_id)->first();
            $id = $article_category->id;
            DB::table('article_category')->whereId($id)->delete();
            $cmd->info("$id fix success");
        }
    }

    public function content($article)
    {
        $body = $article->body;
        $preg = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
        preg_match_all($preg, $body, $matchs);
        $image_tag = $matchs[0][0];
        $image_url = $matchs[1][0];
        $preg      = "/.*?thumbnail_(\d+)/";
        preg_match_all($preg, $image_url, $matchs);
        $video_id = $matchs[1][0];
        if (!empty($image_url)) {
            $article->body      = str_replace($image_tag, '', $body);
            $article->status    = -1;
            $article_categories = $article->categories()->get();
            $newArticle         = Article::where('video_id', $video_id)->first();
            if ($newArticle) {
                foreach ($article_categories as $category) {
                    $newArticle->categories()->attach([$category->id => [
                        'created_at' => $category->pivot->created_at,
                        'updated_at' => $category->pivot->updated_at,
                        'submit'     => $category->pivot->submit,
                    ]]);
                }
            }
            $article->save();
            $cmd->info('Article Id:' . $article->id . ' fix success');
        }
    }

    public static function collections($cmd)
    {
        $cmd->info('fix collections ...');
        Collection::chunk(100, function ($collections) {
            foreach ($collections as $conllection) {
                $conllection_id = $conllection->id;
                if (count($conllection->articles()->pluck('article_id')) > 0) {
                    $article_id_arr = $conllection->articles()->pluck('article_id');
                    foreach ($article_id_arr as $article_id) {
                        $article                = Article::find($article_id);
                        $article->collection_id = $conllection_id;
                        $article->save();
                        $conllection->count_words += $article->count_words;
                        $cmd->info('Artcile:' . $article_id . ' corresponding collections:' . $conllection_id);
                    }
                    $conllection->count = count($article_id_arr);
                    $conllection->save();
                }
                //
            }
        });
        $cmd->info('fix collections success');
    }

    public static function article_comments($cmd)
    {
        //修复Article评论数据
        $cmd->info('fix article comments...');
        Comment::whereNull('comment_id', 'and', true)->chunk(100, function ($comments) {
            foreach ($comments as $comment) {
                if (empty(Comment::find($comment->comment_id))) {
                    $article_id = $comment->commentable_id;
                    $comment->delete();
                    $cmd->info('文章： https://l.ainicheng.com/article/' . $article_id);
                }
            }
        });
        $cmd->info('fix articles count_comments...');
        //修复Article评论统计数据
        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $article->count_replies  = $article->comments()->count();
                $article->count_comments = $article->comments()->max('lou');
                $article->save();
            }
        });
        $cmd->info('fix success');
    }

    public static function visits($cmd)
    {
        Visit::chunk(100, function ($visits) {
            foreach ($visits as $visit) {
                $visit->visited_type = str_plural($visit->visited_type);
                $visit->save(['timestamps' => false]);
            }
        });
    }

    public static function actions($cmd)
    {
        $cmd->info('fix article action');
        Article::where('status', '1')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $article_id    = $article->id;
                $acion_article = Action::where('actionable_type', 'articles')
                    ->where('actionable_id', $article_id)->get();
                if (!$acion_article->count()) {
                    $action = Action::updateOrCreate([
                        'user_id'         => $article->user_id,
                        'actionable_type' => 'articles',
                        'actionable_id'   => $article->id,
                        'created_at'      => $article->created_at,
                        'updated_at'      => $article->updated_at,
                    ]);
                    $cmd->info('fix Article Id:' . $article->id . ' fix success');
                }
            }
        });
        $cmd->info('fix article action success');
    }

    public static function users($cmd)
    {
        //修复上次296用户 id变成144 导致一系列网页错误
        $user = User::find(144);
        $user->id = 296;
        $user->save();
        $cmd->info($user->id.' fix success');
    }

    public static function notifications($cmd)
    {
        $cmd->info('fix notifications ....');
        DB::table('notifications')->whereType('App\Notifications\ArticleCommented')->orderByDesc('id')->chunk(100,function($notifications) use($cmd){
            foreach($notifications as $notification){
                $data = json_decode($notification->data);
                if(isset($data->article_id)){
                    try{
                        $article = Article::findOrFail($data->article_id);
                    $type = '文章';
                    if($article->type == 'video'){
                        $type = '视频';
                    } else if( $article->type == 'post' ){
                        $type = '动态';
                    }
                    $user = User::findOrFail($data->user_id);
                    }
                    catch(\Exception $e){
                        continue;
                    }
                    $msgText1 = '中写了一条新评论';
                    $msgText2 = '评论了你的';
                    $msgText3 = '的评论中提到了你';
                    $title = $data->title;

                    $is_fix = false;

                    if(strpos($title,$msgText1) !== false){
                        $data->title = $user->link() . ' 在' . $type . $article->link() . '中写了一条新评论';
                        $is_fix = true;
                    }else if(strpos($title,$msgText2) !== false){
                        $data->title = $user->link() . ' 评论了你的' . $type . $article->link();
                        $is_fix = true;
                    }else if(strpos($title,$msgText3)){
                         $data->title = $user->link() . ' 在' . $type . $article->link() . '的评论中提到了你';
                         $is_fix = true;
                    }
                    $cmd->info($notification->id.' fix ');

                    if($is_fix){
                        $new_title = $data->title;
                        try{
                            $result = DB::table('notifications')->where('id',$notification->id)->update(['data->title'=>$new_title]);
                            if($result){
                                $cmd->info('notification:'.$notifications->id.' fix success');
                            }
                        }catch(\Exception $e){
                            continue;
                        }
                    }
                }
            }
        });
        $cmd->info('fix success');
    }
}
