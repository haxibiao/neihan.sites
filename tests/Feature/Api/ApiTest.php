<?php

namespace Tests\Feature\Api;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

//这个类下所有的方法都会真正的修改数据库,绝对不能在线上环境跑,只能在本地运行.
class ApiTest extends TestCase
{
    public function testFollowApi()
    {
        $user = \App\User::orderBy('id', 'desc')->take(5)->get()->random();

        $category = \App\Category::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->post("/api/follow/$category->id/categories", [
            "api_token" => $user->api_token,
        ]);

        $response->assertStatus(200);
    }

    public function testLikeApi()
    {
        $user = \App\User::orderBy('id', 'desc')->take(5)->get()->random();

        $article = \App\Article::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->post("/api/like/$article->id/article", [
            'api_token' => $user->api_token,
        ]);
        $response->assertStatus(200);
        $response->assertSeeText('liked');
    }

}
