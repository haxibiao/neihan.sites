<?php

namespace App\Console\Commands;

use App\Article;
use App\Image;
use Illuminate\Console\Command;

class FixData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:data {--traffic} {--articles} {--images} {--videos}';

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
        if ($this->option('traffic')) {
            $this->fix_traffic();
        }

        if ($this->option('articles')) {
            $this->fix_articles();
        }

        if ($this->option('images')) {
            $this->fix_images();
        }

    }

    public function fix_images()
    {
        $this->error('use image:resize ...');
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
