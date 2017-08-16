<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use App\Image;
use App\Video;
use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:data {--traffic} {--articles} {--images} {--videos} {--categories} {--force}';

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

    }

    public function fix_categories()
    {
        $categories = Category::where('type', null)->get();
        foreach ($categories as $category) {
            $article = Article::where('category_id', $category->id)->first();
            if ($article) {
                if (!empty($article->image_url)) {
                    $category->logo = $article->image_url;
                    $category->type = 'article';
                    $category->save();
                    $this->info($category->name . ':' . $category->logo);
                }
            }
        }

        //视频
        $category = Category::firstOrNew([
            'name' => '有意思',
        ]);
        $category->type    = 'video';
        $category->user_id = 1;
        $category->name_en = 'youyisi';
        $category->logo    = Video::first()->cover;
        $category->save();

        $videos = Video::all();
        foreach ($videos as $video) {
            $video->category_id = $category->id;
            $video->save();
            $this->info($category->name . ' - ' . $video->title);
        }

        $category = Category::firstOrNew([
            'name' => '搞笑',
        ]);
        $category->type    = 'video';
        $category->user_id = 1;
        $category->name_en = 'gaoxiao';
        $category->logo    = Video::first()->cover;
        $category->save();
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
        $images = Image::all();
        foreach ($images as $image) {
            if (str_contains($image->path_small, 'jpg.small.jpg')) {
                $this->info('fixed ' . $image->path_small);
                $image->path_small = str_replace('jpg.small.jpg', 'small.jpg', $image->path_small);
                $image->save();
            }
            if (str_contains($image->path_small, 'png.small.png')) {
                $this->info('fixed ' . $image->path_small);
                $image->path_small = str_replace('png.small.png', 'small.png', $image->path_small);
                $image->save();
            }
            if (str_contains($image->path_small, 'gif.small.gif')) {
                $this->info('fixed ' . $image->path_small);
                $image->path_small = str_replace('gif.small.gif', 'small.gif', $image->path_small);
                $image->save();
            }
        }
    }

    public function fix_articles()
    {
        // $article = Article::find(361);
        // $this->fix_article($article);
        // return;

        $articles = Article::with('images')->get();
        foreach ($articles as $article) {
            $this->fix_article($article);
        }
    }

    public function fix_article($article)
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
}
