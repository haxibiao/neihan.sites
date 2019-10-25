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
    }

    public function created(Article $article)
    {
        if ($profile = $article->user->profile) {
            $profile->count_articles = $article->user->publishedArticles()->count();
            $profile->save();
        }

        if ($category = $article->category) {
            $category->count = $category->articles()->count();
            $category->save();
        }
    }

    public function updated(Article $article)
    {
        //
    }

    public function deleted(Article $article)
    {
        //
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
