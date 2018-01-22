<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use App\Comment;
use App\Favorite;
use App\Image;
use App\Like;
use App\Video;
use App\User;
use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:data {--user_count}{--favorite}{--small_image}{--tags}{--comments} {--traffic} {--articles} {--images} {--videos} {--categories} {--force}';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('favorite')) {
            $this->fix_favorite();
        }
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
        if ($this->option('small_image')) {
            $this->fix_images_small();
        }

        if ($this->option('videos')) {
            $this->fix_videos();
        }
        if ($this->option('user_count')) {
            $this->fix_user_count();
        }

    }
    public function fix_favorite()
    {
        Favorite::chunk(100, function ($favorites) {
            foreach ($favorites as $favorite) {
                if ($favorite->faved_type == 'article') {
                    $favorite->faved_type = 'articles';
                    $favorite->save();
                }
                if ($favorite->faved_type == 'video') {
                    $favorite->faved_type = 'videos';
                    $favorite->save();
                }
            }
        });
        Like::chunk(100, function ($likes) {
            foreach ($likes as $like) {
                if ($like->liked_type == 'article') {
                    $like->liked_type = 'articles';
                    $like->save();
                }
                if ($like->liked_type == 'video') {
                    $like->liked_type = 'videos';
                    $like->save();
                }
            }
        });
    }
    public function fix_tags()
    {
        {
            foreach (Article::all() as $article) {
                foreach ($article->tags1 as $tag1) {
                    $article->tags()->save($tag1);
                    $this->info($article->title . ' added tag: ' . $tag1->name);

                }
            }
        }
    }
    public function fix_comments()
    {
        foreach (Comment::all() as $comment) {
            $comment->commentable_type = str_replace('article', 'articles', $comment->commentable_type);
            $comment->commentable_type = str_replace('video', 'videos', $comment->commentable_type);
            $comment->save();
            $this->info($comment->id . ' fixed');
        }
    }

    public function fix_categories()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $article = $category->articles()->orderBy('id')->where('image_url', '<>', '')->first();
            if ($article) {
                $image_url = parse_url($article->image_url, PHP_URL_PATH);
                $this->info($image_url);
                if (file_exists(public_path($image_url))) {
                    $img = \ImageMaker::make(public_path($image_url));
                    $img->resize(300, 200);
                    $img->crop(200, 200, 50, 0);

                    $img->resize(180, 180);
                    $category->logo = $image_url . '.logo.jpg';
                    $img->save(public_path($category->logo));

                    $img->resize(32, 32);
                    $small_logo = $image_url . '.logo.small.jpg';
                    $img->save(public_path($small_logo));

                    $this->info($category->id . ' articles:' . $category->articles()->count() . '-' . $category->name . ' logo:' . $category->logo);
                } else {
                    $this->error($category->id . ' articles:' . $category->articles()->count() . '-' . $category->name . ' 第一文章暂无图片.. ' . $image_url);
                }
            } else {
                $this->comment($category->id . ' articles:' . $category->articles()->count() . '-' . $category->name . ' 暂无文章.. ');
            }

            $category->count = $category->articles()->count();
            $category->save();
        }
    }

    public function fix_videos()
    {
        // $video = Video::find(2);
        // $this->fix_video_cover($video);
        // dd($video);

        $videos = Video::all();
        foreach ($videos as $video) {
            $this->fix_video_cover($video);
        }
    }

    public function fix_video_cover($video)
    {
        $cover = public_path($video->cover);
        if (!file_exists($cover) || $this->option('force')) {
            $video_path = $video->path;
            if (!starts_with($video_path, 'http')) {
                $video_path = env('APP_ENV') == 'local' ? env('APP_URL') . $video->path : public_path($video->path);
            }

            $this->info($video_path);
            $this->info($cover);
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
                    $this->comment('miss ' . $image->path);
                    $try_path = str_replace($image->extension, '', $image->path);
                    if (!file_exists(public_path($try_path))) {
                        $this->error('try path also miss: ' . $try_path);
                    } else {
                        @rename(public_path($try_path), public_path($image->path));
                    }
                }

                $image->save();
                $this->info($image->id . '  ' . $image->extension);
            }
        });
    }
    public function fix_images_small()
    {
        Image::orderBy('id')->chunk(100, function ($images) {
            foreach ($images as $image) {
                if ($image->path == null) {
                    continue;
                }
                if (str_contains($image->path, 'video')) {
                    continue;
                }
                if (!is_file(public_path($image->path))) {
                    continue;
                }

                $extension = pathinfo($image->path, PATHINFO_EXTENSION);
                if ($extension == 'gif') {
                    $image->path_small = '';
                    $image->save();
                    continue;
                }

                $image->extension = $extension;
                //re crop small image
                $img = \ImageMaker::make(public_path($image->path));
                if ($img->width() / $img->height() < 1.5) {
                    $img->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $img->resize(null, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $img->crop(300, 200, 0, 0);
                $image->path_small = '/storage/img/' . $image->id . '.small.' . $extension;
                $img->save(public_path($image->path_small));

                $image->save();

                $this->info($extension . $image->id);
            }
        });
    }

    public function fix_articles()
    {
        //破除内存限制,这里有可能处理大量数据
        ini_set('memory_limit', '-1');

        $article = Article::find(1158);
        $this->info("$article->title delete");
        $article->delete();
    }

    public function fix_article_image($article)
    {
        $this->info($article->image_url);
        //fix image_url
        if (str_contains($article->image_url, 'storage/video/')) {
            $article->image_url = str_replace('.small.jpg', '', $article->image_url);
        }
        if (starts_with($article->image_url, 'http')) {
            $this->comment($article->image_url);
            $article->image_url = parse_url($article->image_url, PHP_URL_PATH);
            $this->info($article->image_url);
        }
        $image_url = $article->image_url;
        if (str_contains($image_url, '.small.')) {
            $image_url = str_replace('.small.jpg', '', $image_url);
            $image_url = str_replace('.small.gif', '', $image_url);
            $image_url = str_replace('.small.png', '', $image_url);
        }
        if (!file_exists(public_path($image_url))) {
            $this->error('miss file for ' . $image_url);
            $article->image_url = '';
        }
        if (empty($article->image_url) && !$article->images->isEmpty()) {
            $article->image_url = $article->images()->first()->path;
        }
        if (!empty($article->image_url)) {
            $image = Image::where('path_origin', $image_url)->orWhere('path', $image_url)->first();
            if ($image) {
                $article->image_url = $image->path;
                $this->comment($image->path);
            }
        }
        //fix image new path in body
        $pattern_img = '/<img src=\"(.*?)\"/';
        if (preg_match_all($pattern_img, $article->body, $matches)) {
            $imgs = $matches[1];
            foreach ($imgs as $img) {
                $this->comment($img);
                if (starts_with($img, 'http')) {
                    $img = parse_url($img, PHP_URL_PATH);
                    $this->info($img);
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
        //     $this->info('fix category: ' . $article->title);
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

            $this->info($traffic->id);
        }
    }

    public function fix_user_count()
    {
        $users =User::orderBy('id','desc')->get();
        

        foreach($users as $user){
             $user->count_words=0;
             $user->count_likes=0;

             $user->count_articles=$user->articles()->count();
             $articles=$user->articles;
             foreach($articles as $article){
                  $article_word=ceil(strlen(strip_tags($article->body)) / 2);
                  $user->count_words=$user->count_words+$article_word;
                  $user->count_likes=$user->count_likes+$article->count_likes; 
             }
             $this->info("$user->name fix");
             $user->save();
        }
    }
}
