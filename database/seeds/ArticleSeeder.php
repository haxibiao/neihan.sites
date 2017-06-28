<?php

use App\Article;
use App\ArticleTag;
use App\Tag;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Article::count() == 0) {
            $article              = new Article();
            $article->title       = '李时珍简介：医药世家出来的一代名医';
            $article->keywords    = '李时珍,李时珍简介';
            $article->description = '李时珍简介李时珍出生于公元1518年的蕲州，他的父亲和爷爷均是当地有名的医生。虽然如此李时珍却被其父要求读书考科举，而非和他们一样从医，因为当时医生这一职业是低贱的，李时珍';
            $article->body        = '[导读]李时珍简介李时珍出生于公元1518年的蕲州，他的父亲和爷爷均是当地有名的医生。虽然如此李时珍却被其父要求读书考科举，而非和他们一样从医，因为当时医生这一职业是低贱的，李时珍
李时珍简介

李时珍出生于公元1518年的蕲州，他的父亲和爷爷均是当地有名的医生。虽然如此李时珍却被其父要求读书考科举，而非和他们一样从医，因为当时医生这一职业是低贱的，李时珍的父亲希望他能为官光耀门楣。

但是李时珍却对从官和名利没有兴趣，还是偷偷的在学医，最后他的父亲知道了他的想法同意了他学医，还给了他很重要的指导。李时珍在这期间便潜心学习，有了很大的进步。后来听从其父的建议注重实践和知识相结合，便一边学习一边治病，积累了很丰富的临床经验。';
            $article->author      = 'pengchong';
            $article->user_id     = 1;
            $article->category_id = 1;
            $article->save();
        }

        $tags = ['中医', '李时珍'];
        foreach ($tags as $tag) {
            $tag_item = Tag::firstOrNew([
                'name' => $tag,
            ]);
            $tag_item->user_id = 1;
            $tag_item->save();

            $article_tag = ArticleTag::firstOrNew([
                'article_id' => 1,
                'tag_id'     => $tag_item->id,
            ]);
            $article_tag->save();
        }
    }
}
