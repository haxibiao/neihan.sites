<?php

namespace Tests\Feature\Web;

use Tests\TestCase;

/**
 * 基本网页访问测试，游客|爬虫模式
 */

class BasicTest extends TestCase
{
    public function testHomePageOK()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testArticleDetailOK()
    {
        $article  = \App\Article::orderBy('id', 'desc')->where('status', '>', 0)->take(10)->get()->random();
        $response = $this->get("/article/{$article->id}");
        $response->assertStatus($article->type == 'video' ? 302 : 200);
    }

    public function testCategoryPageOK()
    {
        $cate_id  = \App\Category::orderBy('id', 'desc')->take(10)->get()->random()->id;
        $response = $this->get("/category/$cate_id");
        $response->assertStatus(200);
    }

    public function testUserPageOK()
    {
        $user     = \App\User::orderBy('id', 'desc')->take(10)->get()->random();
        $response = $this->get("/user/{$user->id}");
        $response->assertStatus(200);
    }

    public function testUserCanLogin()
    {
        $response = $this->post('/login', [
            'email'    => 'author_test@haxibiao.com',
            'password' => 'mm1122',
        ]);

        $response->assertStatus(302);
    }
}
