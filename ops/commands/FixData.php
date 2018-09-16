<?php
namespace ops\commands;

use App\Action;
use App\Article;
use App\Category;
use App\Collection;
use App\Comment;
use App\Image;
use App\Transaction;
use App\User;
use App\Video;
use App\Visit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

    public static function videos($cmd)
    {
        $cmd->info('fix videos ...');
        Video::whereNotNull('qcvod_fileid')->chunk(100, function ($videos) use ($cmd) {
            foreach ($videos as $video) {
                sleep(2);
                $cmd->info('正在处理>>>' . $video->id . '<<<');
                try {
                    //保存article与video的原始信息，限制影响的范围
                    $article = $video->article;
                    if( !empty($article) ){
                        $status = $article->status;
                        $cover = $article->image_url;
                        $updated_at = $article->updated_at;
                    }
                    
                    $video_status = $video->status;
                    $video_title  = $video->title;
                    $video_updated_at = $video->updated_at;
                    
                    $video->syncVodProcessResult();
                    
                    if( !empty($article) ){
                        $article->status = $status;
                        $article->image_url = $cover;
                        $article->updated_at = $updated_at;
                        $article->timestamps = false;
                        $article->save();
                    }

                    $video->status = $video_status;
                    $video->title = $video_title;
                    $video->updated_at = $video_updated_at;
                    $video->timestamps = false;
                    $video->save();

                } catch (\Exception $e) {
                    $cmd->error('视频id为>>>' . $video->id . '<<<拉取失败');
                    continue;
                }
            }
        });
        $cmd->info('fix videos finished...');
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
        $cmd->info('fix source_url default null ...');
        DB::statement("update articles set source_url = null where source_url = '0'");
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
        $user     = User::find(144);
        $user->id = 296;
        $user->save();
        $cmd->info($user->id . ' fix success');
    }

    public static function notifications($cmd)
    {
        $cmd->info('fix notifications ....');
        DB::table('notifications')->whereType('App\Notifications\ArticleLiked')->orderByDesc('id')->chunk(100, function ($notifications) use ($cmd) {
            foreach ($notifications as $notification) {
                $data = json_decode($notification->data);
                if (strpos($data->body, '视频') !== false && strpos($data->title, '《》') !== false) {
                    try{
                        $article       = Article::findOrFail($data->article_id);
                        $article_title = $article->title ?: $article->video->title;
                        // 标题 视频标题都不存在 则取description
                        if (empty($article_title)) {
                            $article_title = $article->get_description();
                        }
                        $notification->timestamps = false;
                        $result = DB::table('notifications')->where('id',$notification->id)
                            ->update(
                                [
                                    'data->article_title'=>$article_title,
                                    'data->title'=>'《' . $article_title . '》'
                                ]
                            );
                        if($result){
                            $cmd->info('notification ' . $notification->id . ' fix success');
                        }
                    }catch(\Exception $e){
                       continue;
                    }
                }
            }
        });
        $cmd->info('fix success');
    }

    public function transactions($cmd)
    {
        // Transaction::whereType('打赏')->orderByDesc('id')->chunk(100,function($transactions) use($cmd){
        //     foreach ($transactions as $transaction) {
        //         try{
        //             preg_match_all('#/(\d+)#', $transaction->log, $matches);
        //             $data = end($matches);
        //             $user = User::findOrFail(reset($data));
        //             $video = Video::findOrFail(end($data));
        //         }catch(\Exception $e){
        //             continue;
        //         }
        //         if(!empty($article = $video->article) && !empty($user)){
        //             if(strpos($transaction->log,'向您的') !== false && strpos($transaction->log,'《》') !== false){
        //                 $transaction->log = $user->link() . '向您的' . $article->link() . '打赏' . $amount . '元';
        //                 $transaction->timestamps=false;
        //                 $transaction->save();
        //                 $cmd->info('transaction '.$transaction->id.' fix success');
        //             }else if(strpos($transaction->log,'向<a') !==false && strpos($transaction->log,'《》') !== false){
        //                 $transaction->log = '向' . $article->user->link() . '的' . $article->link() . '打赏' . $amount . '元';
        //                 $transaction->timestamps=false;
        //                 $transaction->save();
        //                 $cmd->info('transaction '.$transaction->id.' fix success');
        //             }
        //         }
        //     }
        // });
        Transaction::whereType('打赏')->orderByDesc('id')->chunk(100, function ($transactions) use ($cmd) {
            foreach ($transactions as $transaction) {
                if (strpos($transaction->log, '赏元') !== false) {
                    $log                     = str_replace('赏元', '赏' . intval($transaction->amount) . '元', $transaction->log);
                    $transaction->log        = $log;
                    $transaction->timestamps = false;
                    $transaction->save();
                    $cmd->info('transaction ' . $transaction->id . ' fix success');
                }
            }
        });
    }
}
