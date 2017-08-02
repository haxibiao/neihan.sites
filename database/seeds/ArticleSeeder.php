<?php

use App\Article;
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

        if (env('APP_ENV') == 'local') {
            $this->run_local();
        }
    }

    public function run_local()
    {
        $article = Article::firstOrNew([
            'id' => 1,
        ]);

        $article->title       = '测试';
        $article->keywords    = '测试';
        $article->description = '测试';
        $article->author      = '老张';
        $article->user_id     = 1;
        $article->category_id = 1;
        $article->has_pic     = 0;
        $article->body        = '<p>测试正文</p> <p><single-list article-id="1" data-key="2"></single-list></p> ';
        $article->body .= '<p><single-list article-id="1" data-key="1"></single-list></p>';
        $article->body .= '<p><single-list article-id="1" data-key="0"></single-list></p>';

        //添加测试json
        $data[0] = [
            'title' => '搭配英雄',
            'type'  => 'single_list',
            'col'   => 'col-md-6',
            'aids' => [10, 11, 14, 13],
        ];
        $data[1] = [
            'title' => '针对英雄',
            'type'  => 'single_list',
            'col'   => 'col-md-6',
            'aids' => [10, 11, 14, 13],
        ];
        $data[2] = [
            'title' => '相关文章必读',
            'type'  => 'single_list',
            'col'   => 'col-md-12',
            'aids' => [10, 11, 14, 13],
        ];

        $article->json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $article->save();
    }
}
