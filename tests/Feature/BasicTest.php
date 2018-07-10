<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomePageOK()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testArticleDetailOK()
    {
        $rand_article_id = \App\Article::orderBy('id', 'desc')->where('status', '>', 0)->take(10)->get()->random()->id;
        $response        = $this->get("/article/$rand_article_id");
        $response->assertStatus(200);
    }

    public function testCategoryPageOK()
    {
        $name_en  = \App\Category::orderBy('id', 'desc')->take(10)->get()->random()->name_en;
        $response = $this->get("/$name_en");
        $response->assertStatus(200);
    }

    public function testUserPageOK()
    {
        $rand_user_id = \App\User::orderBy('id', 'desc')->take(10)->get()->random()->id;
        $response     = $this->get("/user/$rand_user_id");
        $response->assertStatus(200);
    }

    public function testUserCanLogin()
    {
        $response = $this->post('/login', [
            'email'    => 'author_test@haxibiao.com',
            'password' => 'mm1122',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/settings');
    }

    public function testUserRegister()
    {
        // $response=$this->get('/');

        // $response->assertStatus(200);
        $response = $this->post('/register', [
            'name'     => 'wangxin',
            'email'    => time() . 'test@haxibiao.com',
            'password' => '123123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/settings');
    }

}
