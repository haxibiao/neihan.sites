<?php

namespace App\Jobs;

use App\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticleDelay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //max line
    public $tries = 3;

    protected $article_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($article_id)
    {
        $this->article_id = $article_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $article_id      = $this->article_id;
        $article         = Article::find($article_id);
        $article->status = 1;
        $article->created_at =$article->delay_time;
        $article->delay_time = null;
        $article->save();
    }
}
