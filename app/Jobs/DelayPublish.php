<?php

namespace App\Jobs;

use App\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DelayPublish implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    protected $article_id;
    public function __construct($article_id)
    {
        $this->article_id = $article_id;
    }
    public function handle()
    {
        $article_id      = $this->article_id;
        $article         = Article::find($article_id);
        $article->status = 1;
        $article->save();
    }
}
