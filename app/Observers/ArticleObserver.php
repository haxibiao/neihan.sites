<?php

namespace App\Observers;

use App\article;

class ArticleObserver
{
    public function creating(Article $article)
    {
        // nova create with user id
        if (empty($article->user_id)) {
            $article->user_id = Auth()->id();
        }
        $article->count_words = ceil(strlen(strip_tags($article->body)) / 2);

        //TODO: 文章内图片或者视频更新时？ cover要变？
        if ($article->image) {
            $article->cover_path = $article->image->path_small();
        }
        if ($article->video) {
            $article->cover_path = $article->video->cover;
        }
    }

    public function created(Article $article)
    {
        $article->recordAction();

        if ($profile = $article->user->profile) {
            $profile->count_articles = $article->user->publishedArticles()->count();
            $profile->count_words    = $article->user->publishedArticles()->sum('count_words');
            $profile->save();
        }

        if ($category = $article->category) {
            $category->count = $category->articles()->count();
            $category->save();
            //同步多对多的关系
            $article->hasCategories()->syncWithoutDetaching([$category->id]);
        }

        if ($article->status == 1) {
            //可能是发布了文章，需要统计文集的文章数，字数
            if ($collection = $article->collection) {
                $collection->count       = $collection->articles()->count();
                $collection->count_words = $collection->articles()->sum('count_words');
                $collection->save();
            }
        }

    }

    public function updated(Article $article)
    {
        //TODO: 更多需要更新文章数和字数的场景需要写这里...
        //TODO: 文章软删除时
        if ($article->status = 0) {
            $article->update([
                'submit' => Article::REFUSED_SUBMIT,
            ]);
        }
    }

    public function deleted(Article $article)
    {
        //TODO：文章彻底删除
    }

    public function restored(Article $article)
    {
        //
    }

    public function forceDeleted(Article $article)
    {
        //
    }
}
