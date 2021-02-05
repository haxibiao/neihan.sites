<?php

namespace App\Console\Commands;

use App\Article;
use App\Movie;
use Illuminate\Console\Command;

class FixData extends Command
{

    protected $signature = 'fix:data {table}';

    protected $description = 'fix dirty data by table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        if ($table = $this->argument('table')) {
            return $this->$table();
        }
        return $this->error("必须提供你要修复数据的table");
    }

    public function movies()
    {
        $this->info("清空之前的movies");
        Movie::truncate();
    }

    public function fixMediaArticleSourceId()
    {
        $site = config('app.name');
        \DB::connection('media')->table('articles')->where('source', $site)->chunkById(100, function ($articles) {
            foreach ($articles as $mediaArticle) {
                $article = Article::where('title', $mediaArticle->title)->first();
                if (!$article) {
                    $this->error("{$mediaArticle->id} 找不到对应的 article {$mediaArticle->title}");
                    return;
                }
                $mediaArticle->update([
                    'source_id' => $article->id,
                ]);
                $this->info("{$mediaArticle->source_id} 已修复 source_id article {$mediaArticle->title}");
            }
        });
    }
}
