<?php

namespace tools\commands;

use App\Article;
use App\Category;
use App\Comment;
use App\Image;
use App\User;
use App\Video;
use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = <<<EOT
        fix:data
            {--tags}
            {--comments}
            {--traffic}
            {--articles}
            {--images}
            {--videos}
            {--categories}
            {--force}
            {--users}
EOT;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '--traffic: fix existing traffic date string, day of year etc ....';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($commander)
    {
       $this->commander = $commander;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('tags')) {
            $this->fix_tags();
        }

        if ($this->option('comments')) {
            $this->fix_comments();
        }

        if ($this->option('categories')) {
            $this->fix_categories();
        }

        if ($this->option('traffic')) {
            $this->fix_traffic();
        }

        if ($this->option('articles')) {
            $this->fix_articles();
        }

        if ($this->option('images')) {
            $this->fix_images();
        }

        if ($this->option('videos')) {
            $this->fix_videos();
        }

        if ($this->option('users')) {
            $this->fix_users();
        }
    }

    public function fix_users()
    {
        //fix api_token to unique  ..
        //foreach (User::all() as $user) {
        //    $user->api_token = str_random(60);
        //    $user->save();
        //    $this->commander->info("$user->name : $user->api_token");
        //}
$names = ['厕所里蹦迪',
'流年染指寂寞',
'仅有的唯一是你',
'人生自编自导自演',
'机电一体┽沧海',
'21尐傻瓜',
'晚上找人陪1',
'俺想找gg爱',
'戒不掉的烟',
'行同陌路',
'不龍魂の轼爱',
'出人头地',
'半面情绪',
'只可远观',
'一人一心',
'如愿好吗',
'疯疯癫癫小青年',
'有些个故事忘不掉',
'玫瑰小米',
'也许べ已没有也许',
'誓识是笑话而已',
'痴人夸梦',
'心碎半条街',
'不好不坏',
'故事悄然落幕',
'一点小情绪',
'旧人旧事旧心烦',
'別跟自己過不去',
'颠覆骄傲的抬起头',
'正在读取中',
'人荡无罪物荡心碎',
'掏心止痛',
'蝴蝶与蓝',
'め情绪化',
'笑不一定很海',
'右手年华',
'想待在角落',
'優柔寡斷',
'粉色味蕾的悲伤',
'心以被傷',
'幼稚范er',
'少在姐面前装b',
'视你如命',
'三年五年',
'零碎記忆',
'放肆嘚寂寞只因爲你',
'你給的忽略',
'失心失人',
'调儿啷当',
'等你一生永不悔',
'疯疯癫癫的小可爱',
'捂着心脏说疼',
'放肆的青春诠释了悲伤',
'唯独是你',
'メ错了而已',
'九月你好',
'雨不眠的下',
'疯人也有疯情调',
'寂寞私聊',
'心随你远行',
'不想放弃你懂么',
'真心无限期',
'只是一个笑话么',
'一二③木头人',
'ヅ不存在的存在',
'旧人勿恋',
'小娘年少不无知',
'半醉半醒半想你',
'青春的滋味',
'为情的妞',
'+偌ホ守不謧',
'训狗小子',
'无地自容',
'ヅ忆搁浅',
'情人总份份合合',
'浮伤↙渲染流年',
'潇洒不放纵',
'誓信﹀贓',
'男流氓霸道',
'の月莉天使',
'诚实小流氓ce',
'傻瓜笨蛋在默默的想你',
'ㄣ悲剧小丑',
'独领风情',
'奋斗的小青年',
'紧存德依赖',
'ぃ懵懵懂懂小清新',
'懂人自懂',
'不该有的情绪',
'落寞年华',
'你知不知道有你真好',
'紳士范er',
'放肆才叫青春',
'有曙光不见得有希望',
'少年你是谁的英雄',
'薄荷味的小清新',
'梦幻之冰',
'优雅的颓丧',
'じ愛你一丗',
'早知是梦',
'装什么纯天然',
'与众不同的范er'];

        for ($x = 1; $x <= 100; $x++) {
            $user = User::firstOrNew([
                'email' => 'a' . $x . '@haxibiao.com',
            ]);
            $user->name = $names[$x];
            $this->commander->info($user->email . ' 更换名字为 ' . $names[$x]);
            $user->save();
        }
    }

    public function fix_tags()
    {
        foreach (Article::all() as $article) {
            foreach ($article->tags1 as $tag1) {
                $article->tags()->save($tag1);
                $this->commander->info($article->title . ' added tag: ' . $tag1->name);
            }
        }
    }

    public function fix_comments()
    {
        foreach (Comment::all() as $comment) {
            $comment->commentable_type = str_replace('article', 'articles', $comment->commentable_type);
            $comment->commentable_type = str_replace('video', 'videos', $comment->commentable_type);
            $comment->save();
            $this->commander->info($comment->id . ' fixed');
        }
    }

    public function fix_categories()
    {
        $category =Category::find(98);

        $articles =$category->articles;

        foreach($articles as $article){
            $article->category_id=96;

            $article->categories()->sync(96);

            $article->save(['timestamp'=>false]);

            $this->commander->info('fix success'.$article->id);
        }

        $category=Category::find(99);

        $articles=$category->articles;

        foreach($articles as $article){
            $article->category_id=97;

            $article->categories()->sync(97);
            
            $article->save(['timestamp'=>false]);

            $this->commander->info('fix success'.$article->id);
        }
     
    }

    public function fix_videos()
    {
        $videos = Video::all();
        foreach ($videos as $video) {
            $this->fix_video_cover($video);
        }
    }

    public function fix_video_cover($video)
    {
        $cover = public_path($video->cover);
        if (!file_exists($cover) || $this->force) {
            $video_path = $video->path;
            if (!starts_with($video_path, 'http')) {
                $video_path = env('APP_ENV') == 'local' ? env('APP_URL') . $video->path : public_path($video->path);
            }

            $this->commander->info($video_path);
            $this->commander->info($cover);
            $this->make_cover($video_path, $cover);
        }
    }

    public function make_cover($video_path, $cover)
    {
        $second = rand(2, 4);
        if (!starts_with($video_path, 'http')) {
            if (file_exists($video_path) && filesize($video_path) > 600 * 1000) {
                $second = rand(5, 8);
            }
            if (file_exists($video_path) && filesize($video_path) > 1000 * 1000) {
                $second = rand(8, 14);
            }
        }
        if (str_contains($video_path, '_basic')) {
            $second = rand(14, 18);
        }

        $cmd = "ffmpeg -i $video_path -deinterlace -an -s 300x200 -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $cover 2>&1";
        $do  = `$cmd`;
        return $do;
    }

    public function fix_images()
    {
        Image::chunk(1000, function ($images) {
            foreach ($images as $image) {
                $extension = pathinfo($image->path, PATHINFO_EXTENSION);
                if (strlen($extension) < 3) {
                    $extension = 'jpg';
                }
                $image->extension = $extension;
                $image->path      = ends_with($image->path, $image->extension) ? $image->path : str_replace('.', '', $image->path) . '.' . $image->extension;
                if (!file_exists(public_path($image->path))) {
                    $this->commander->comment('miss ' . $image->path);
                    $try_path = str_replace($image->extension, '', $image->path);
                    if (!file_exists(public_path($try_path))) {
                        $this->commander->error('try path also miss: ' . $try_path);
                    } else {
                        @rename(public_path($try_path), public_path($image->path));
                    }
                }

                $image->save();
                $this->commander->info($image->id . '  ' . $image->extension);
            }
        });
    }

    public function fix_articles()
    {
       Article::orderBy('id')->chunk(100,function($articles){
            foreach($articles as $article){
                if((str_contains($article->image_url,'haxibiao')|| str_contains($article->title,'QQ情侣网名')) && $article->source_url=='0'){
                        $article->source_url=1;
                        $article->timestamps=false;
                        $article->save();

                        $this->commander->info($article->id.'fix success');
                }
            }
       });
    }

    public function fix_article_image($article)
    {
        $this->commander->info($article->image_url);
        //fix image_url
        if (str_contains($article->image_url, 'storage/video/')) {
            $article->image_url = str_replace('.small.jpg', '', $article->image_url);
        }
        if (starts_with($article->image_url, 'http')) {
            $this->commander->comment($article->image_url);
            $article->image_url = parse_url($article->image_url, PHP_URL_PATH);
            $this->commander->info($article->image_url);
        }
        $image_url = $article->image_url;
        if (str_contains($image_url, '.small.')) {
            $image_url = str_replace('.small.jpg', '', $image_url);
            $image_url = str_replace('.small.gif', '', $image_url);
            $image_url = str_replace('.small.png', '', $image_url);
        }
        if (!file_exists(public_path($image_url))) {
            $this->commander->error('miss file for ' . $image_url);
            $article->image_url = '';
        }
        if (empty($article->image_url) && !$article->images->isEmpty()) {
            $article->image_url = $article->images()->first()->path;
        }
        if (!empty($article->image_url)) {
            $image = Image::where('path_origin', $image_url)->orWhere('path', $image_url)->first();
            if ($image) {
                $article->image_url = $image->path;
                $this->commander->comment($image->path);
            }
        }
        //fix image new path in body
        $pattern_img = '/<img src=\"(.*?)\"/';
        if (preg_match_all($pattern_img, $article->body, $matches)) {
            $imgs = $matches[1];
            foreach ($imgs as $img) {
                $this->commander->comment($img);
                if (starts_with($img, 'http')) {
                    $img = parse_url($img, PHP_URL_PATH);
                    $this->commander->info($img);
                }
                $image = Image::where('path_origin', $img)->first();
                if ($image) {
                    $article->body = str_replace($img, $image->path, $article->body);
                }
            }
        }

        // $category = \App\Category::find($article->category_id);
        // if (!$category) {
        //     $article->category_id = \App\Category::first()->id;
        //     $article->save();
        //     $this->commander->info('fix category: ' . $article->title);
        // }

        // //fix date
        // $article->date = $article->created_at->toDateString();

        $article->save();
    }

    public function fix_traffic()
    {
        $traffics = \App\Traffic::all();
        foreach ($traffics as $traffic) {
            $created_at = $traffic->created_at;

            if (empty($traffic->date)) {
                $traffic->date  = $created_at->format('Y-m-d');
                $traffic->year  = $created_at->year;
                $traffic->month = $created_at->month;
                $traffic->day   = $created_at->day;

                $traffic->dayOfWeek   = $created_at->dayOfWeek;
                $traffic->dayOfYear   = $created_at->dayOfYear;
                $traffic->daysInMonth = $created_at->daysInMonth;
                $traffic->weekOfMonth = $created_at->weekOfMonth;
                $traffic->weekOfYear  = $created_at->weekOfYear;
            }

            //fix article_id, category, user_id
            if (starts_with($traffic->path, 'article/')) {
                $article_id = str_replace('article/', '', $traffic->path);
                if (is_numeric($article_id)) {
                    $traffic->article_id = $article_id;
                    $article             = \App\Article::with('category')->find($article_id);
                    if ($article) {
                        if ($article->category) {
                            $traffic->category = $article->category->name;
                        }
                        $traffic->user_id = $article->user_id;
                    }
                }
            }

            $traffic->save();

            $this->commander->info($traffic->id);
        }
    }
}
