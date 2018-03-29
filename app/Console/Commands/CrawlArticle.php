<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use App\Image;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrawlArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:article {--api=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'usage:l.haxibiao.com/api/articies/{count}/{category_name}/crawl?skip=???';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('api')) {
            $api = $this->option('api');
            $this->get_article($api);
        }
    }

    public function get_article($api)
    {
        $articles = @file_get_contents($api);

        if(empty($articles)){
              dd("articles empty");
        }
        $articles = json_decode($articles);

        foreach ($articles as $index => $article) {
            $user_id      = rand(44, 143);
            $article_item =Article::firstOrNew([
                'source_url'=>"https://haxibiao.com/article/$article->id"
            ]);

            $unique=Article::where('source_url',"https://haxibiao.com/article/$article->id")->first();
            if(!empty($unique)){
                $this->error("$article->id unique");
                continue;
            }

            $article_item->title   = $article->title;
            $article_item->body    = $article->body;
            $article_item->status  = 1;
            $article_item->user_id = $user_id;

            // category relations
            $categories = Category::whereIn('name', [
                '唯美图片',
            ])->get()
            ;

            $category = $categories->random();

            $category->count=$category->count();

            $category->save();

            $article_item->category_id = $category->id;
            $article_item->save();

            $this->comment("$article_item->id article save success");

            $preg = '/<img.*?src="(.*?)".*?>/is';

            preg_match_all($preg, $article->body, $match);

            if (!empty($match[1])) {
                $article_item->image_url = $match[1][0];

                foreach ($match[1] as $image_url) {
                    $image        = new Image();
                    $image->title = $article_item->title;
                    $image->path  = $image_url;
                    $image->save();

                    $article_item->images()->syncWithoutDetaching($image->id);

                    $this->info("$image->id image save success");
                }
            }

            $article_item->categories()->syncWithoutDetaching($category->id);

            $article_item->save();

            DB::table('article_category')->where('article_id', $article_item->id)->update(['submit' => '已收录']);

            sleep(15);
        }
    }
}
