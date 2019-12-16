<?php

namespace App\Console\Commands;

use App\Article;
use Illuminate\Console\Command;

class MarkHotPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:hotpost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '标记系统热门视频';

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
        $comments = 3;
        $likes    = 10;
        Article::withCount(['comments','likes'])->whereNotNull('video_id')->whereStatus(1)->whereSubmit(1)->chunk(100,function ($articles) use($comments,$likes){
            foreach ($articles as $article){
                $countComments = $article->comments_count;
                $countLikes = $article->likes_count;
                if($countComments >= $comments || $countLikes >= $likes){
                    $article->is_hot = true;
                    $article->save(['timestamps',false]);
                }
            }
        });
    }
}
