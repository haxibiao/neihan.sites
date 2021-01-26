<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\User;

class LikeTest extends GraphQLTestCase
{

    protected $u1, $article;

    // 测试开始调用方法
    protected function setUp(): void
    {
        parent::setUp();
        $this->u1 = User::factory()->make();
        $this->article = Article::factory()->make();
    }

    // 测试结束调用方法
    protected function tearDown(): void
    {
        $this->u1->forceDelete();
        $this->article->forceDelete();

        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $token = $this->u1->api_token;
        $article_id = $this->article->id;

        // printf($article_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $article_id,
            'type' => 'articles',
            'undo' => true,
        ];
        $this->runGQL($query, $variables, $headers);

    }

}
