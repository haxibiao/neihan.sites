<?php

namespace App\Traits;

use App\Category;
use Auth;

trait ArticleCount
{
    public function article_count($category_ids, $article)
    {
        if ($article->status > 0) {
            //update category article_count
            foreach ($category_ids as $category_id) {
                $category        = Category::find($category_id);
                $category->count = $category->articles()->count();
                $category->save();
            }
        }
    }

    public function article_coment_count($article)
    {
        $article->count_replies = $article->comments()->count();
        $article->count_likes   = $article->likes()->count();
        $article->save();
    }

    public function article_user_count($word)
    {
        $user = Auth::user();
        $user->count_articles++;
        $user->count_words = $user->count_words + $word;
        $user->save();
    }
}
