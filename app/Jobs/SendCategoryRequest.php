<?php

namespace App\Jobs;

use App\Article;
use App\Category;
use App\Notifications\CategoryRequested;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCategoryRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $article;
    protected $category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article, Category $category)
    {
        $this->article  = $article;
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $category = $this->article->allCategories()
            ->where('categories.id', $this->category->id)
            ->first();
        if ($category && $category->pivot && $category->pivot->submit == '待审核') {
            //给所有专题管理发通知
            foreach ($category->admins as $admin) {
                $admin->notify(new CategoryRequested($this->category, $this->article));
                $admin->forgetUnreads();
            }
            //also send to creator
            $category->user->notify(new CategoryRequested($this->category, $this->article));
            $category->user->forgetUnreads();

            //TODO::如果后面撤回了，这个标题也留这了
            $category->new_request_title = $this->article->title;
            //更新单个专题上的新请求数
            $category->new_requests = $category->articles()->wherePivot('submit', '待审核')->count();
            $category->save();
        }
    }
}
