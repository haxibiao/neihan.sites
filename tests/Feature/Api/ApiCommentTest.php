<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

//这个类下所有的方法都会真正的修改数据库,绝对不能在线上环境跑,只能在本地运行.
class ApiCommentTest extends TestCase
{

    public function testComment()
    {
        $user    = \App\User::orderBy('id', 'desc')->take(5)->get()->random();
        $article = \App\Article::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->json("POST", "/api/comment", [
            'api_token'         => $user->api_token,
            'body'              => 'test',
            'commentable_id'    => $article->id,
            'commentable_type'  => "articles",
            'is_new'            => true,
            'is_replay_comment' => false,
            'likes'             => 0,
            'lou'               => 1,
            'reports'           => 0,
            'time'              => time(),
        ]);

        $response->assertStatus(200);
        $content = $response->getOriginalContent();
        $response->assertJson([
            'body' => 'test',
        ]);
    }

}
