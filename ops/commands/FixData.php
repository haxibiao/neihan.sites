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
    //图片迁移第一步：上传静态资源到COS
    public static function images1($cmd)
    {
        ini_set("memory_limit","-1");//取消PHP内存限制
        $cmd->info('fix images1 ...');
        //dd($images);
        $bucket = env('DB_DATABASE'); 
        $cos    = app('qcloudcos'); 
        //忽略下列文件
        $discard_files = [
            '.DS_Store',
            '.git',
        ];
        /* 将img与image文件夹下的内容拷贝到COS */
        $disk   = \Storage::disk('opendir');
        $images = array_merge(
            //$disk->allFiles('/storage/avatar'),
            $disk->allFiles('/storage/img'),
            $disk->allFiles('/storage/image'),
            $disk->allFiles('/storage/category')  
        ); 
        //上传的文件总数
        $sum = count($images);
        $error_count = 0;//记录传输失败的次数
        foreach (collect($images)->chunk(200) as $chunk) {
            foreach ($chunk as $name) {
                $time = $sum/30 + $sum/10;
                var_dump($name .'<<>>'.'剩余:'. $sum  . '个文件,预计剩余时间:' . intval($time) . '秒');
                $base_name = basename($name);
                if(str_contains($base_name , $discard_files)){
                    continue;
                }
                $dstFpath  = $name;
                $srcFpath  = public_path($dstFpath);
                
                try {
                    $result = $cos::upload($bucket, $srcFpath, $dstFpath);
                    $result_obj = json_decode($result); 
                    if($result_obj->code != 0){ //0:代表上传成功的状态
                        $cmd->error('Upload Failure:'. $result_obj->message); 
                        $error_count++;
                    } 
                } catch (\Exception $e) {
                    $error_count++;
                    $cmd->error('图片上传至COS失败---->>>' . $e->getMessage());
                    continue;
                }
                $sum --;
            }
            sleep(1);
        }
        $cmd->info('共:'. $sum .'个文件,失败:'. $error_count .'个文件');
        //处理上传的默认头像
        for ($i = 1; $i <= 15 ; $i++) {
            $srcFpath = public_path('/images/avatar-'.$i.'.jpg');
            $result = $cos::upload($bucket, $srcFpath, 'storage/avatar');
            try {
                $result_obj = json_decode($result);
                if($result_obj->code != 0){ //0:代表上传成功的状态
                    $cmd->error('Upload Failure:'. $result_obj->message); 
                }
            } catch (\Exception $e) {
                $cmd->error('头像上传至COS失败---->>>' . $e->getMessage());
                continue;
            }
        }
    }

    //图片迁移第二步：更新数据库记录中的图片路径
    public static function images2($cmd){
        $cmd->info('fix images2 ...');
        
        //修改Image中的path与path_top
        $path_formatter = 'http://' . env('DB_DATABASE') . '-'. config('qcloudcos.app_id') .'.file.myqcloud.com/storage/image/%s';
        $hxb_path_formatter = 'http://haxibiao-1251052432.file.myqcloud.com/storage/image/%s';
        Image::orderBy('id')->chunk(100, function ($images) use ($cmd,$path_formatter,$hxb_path_formatter) {
            foreach ($images as $image) {
                $source_url = $image->source_url;
                //source_url为空代表爬虫文章
                if( is_null($source_url) ){
                    //原图
                    $image->path     = sprintf($path_formatter, basename($image->path));
                    //轮播图
                    $path_top = $image->path_top;
                    if(!empty( $path_top )){
                         $image->path_top = sprintf($path_formatter, basename($image->path_top));
                    }
                    $image->disk = config('qcloudcos.location'); 
                } else {
                    $image->path     = sprintf($hxb_path_formatter, basename($image->path));
                    //更新轮播图
                    $path_top = $image->path_top;
                    if(!empty( $path_top )){
                         $image->path_top = sprintf($hxb_path_formatter, basename($image->path_top));
                    }
                }
                $image->timestamps = false;
                $image->save();
            }
        });
        
        //修改Category的logo与logo_app的地址
        $logo_formatter = 'http://' . env('DB_DATABASE') . '-'. config('qcloudcos.app_id') .'.file.myqcloud.com/storage/category/%s';
        Category::orderBy('id')->chunk(100, function ($categories) use ($cmd,$logo_formatter) {
            foreach ($categories as $category) {     
                $logo           = $category->logo;
                $category->logo = sprintf($logo_formatter, basename($logo));
                $logo_app       = $category->logo_app;
                if( !empty($logo_app) ){
                    //category中logo_app有部分脏数据
                    if( str_contains($logo_app, ['/tmp']) ){
                        $category->logo_app = sprintf(
                            $logo_formatter, 
                            $category->id . '.logo.app.jpg'
                        );
                    }
                }
                $category->timestamps = false;
                $category->save();
            }
        });

        //修改User的avatar地址
        $avatar_formatter = 'http://' . env('DB_DATABASE') . '-'. config('qcloudcos.app_id') .'.file.myqcloud.com/storage/avatar/%s';
        User::chunk(100,function($users) use ($cmd,$avatar_formatter){
            foreach ($users as $user) {
                $avatar = $user->avatar;
                //统一下各个站点的默认头像
                if(str_contains($avatar, ['default.jpg','avatar.jpg','editor_'])){
                    $user->avatar = sprintf($avatar_formatter,'avatar-' . rand(1, 15) . '.jpg'); 
                } else {
                    $user->avatar = sprintf($avatar_formatter,basename($avatar));
                }
                $user->timestamps = false;
                $user->save();
            }
        });
    }
    //图片迁移第三步：替换文章体中的图片
    public static function images3(){
        Article::chunk(100,function($articles) use ($cmd){
            foreach ($articles as $article) {
                if( empty($article->body) ){
                    continue;
                }
                //匹配正文中所有的图片路径
                $pattern = "/<img.*?src=['|\"](.*?)['|\"].*?[\/]?>/iu";
                preg_match_all($pattern, $body_html, $matches);
                $img_urls = end($matches);
                $body_html = $article->body;
                foreach ($img_urls as $img_url) {
                    if(empty($img_url)){
                        continue;
                    }
                    $img_name = basename($img_url);
                    if(empty($img_name)){
                        continue;
                    }
                    //爱你城本地的图片
                    if( str_contains($img_url, ['https://ainicheng.com/','https://www.ainicheng.com']) ){
                        $formatter = 'http://' . env('DB_DATABASE') . '-'. config('qcloudcos.app_id') .'.file.myqcloud.com/storage/image/%s';
                        $cdn_url = sprintf($formatter, $img_name);
                        $body_html = str_replace($img_url, $cdn_url, $body_html);
                    //哈希表的图片
                    } elseif ( str_contains($img_url, ['https://haxibiao.com/','https://www.haxibiao.com']) ){
                        $formatter = 'http://haxibiao-'. config('qcloudcos.app_id') .'.file.myqcloud.com/storage/image/%s';
                        $cdn_url = sprintf($formatter, $img_name);
                        $body_html = str_replace($img_url, $cdn_url, $body_html);
                    }
                    $body_html = str_replace($img_url, $cdn_url, $body_html);
                }
                $article->timestamps = false;
                $article->save();
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
