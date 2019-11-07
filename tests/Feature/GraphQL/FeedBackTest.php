<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\User;

class FeedBackTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create([
            'api_token' => str_random(60),
        ]);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    //接口没写
    public function testcreateFeedbackMutation()
    {
        $query = file_get_contents(__DIR__ . '/FeedBack/Mutation/createFeedbackMutation.gql');

        $article = Article::inRandomOrder()->first();

        $image = file_get_contents(__DIR__ . '/FeedBack/image1');

        $token = $this->user->api_token;

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        //只传字符串
        $variables = [
            'content' => "测试反馈",
        ];

        $this->startGraphQL($query, $variables, $headers);

        //传图片和内容
        $variables = [
            'content' => "测试反馈",
            'images'  => [$image, $image],
        ];

        $this->startGraphQL($query, $variables, $headers);

        //传图片和联系方式以及内荣
        $variables = [
            'content' => "测试反馈",
            'images'  => [$image, $image],
            "contact" => "123456",
        ];

        $this->startGraphQL($query, $variables, $headers);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testFeedbacksQuery()
    {
        $query = file_get_contents(__DIR__ . '/FeedBack/Query/FeedbacksQuery.gql');

        $token = $this->user->api_token;

        $user = User::inRandomOrder()->first();

        // $variables = [
        //     'count' => 10
        // ];

        $this->startGraphQL($query);
    }

    public function testMyFeedbackQuery()
    {
        $query = file_get_contents(__DIR__ . '/FeedBack/Query/MyFeedbackQuery.gql');

        $token = $this->user->api_token;

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $variables = [
            'id' => $this->user->id,
        ];

        $this->startGraphQL($query, $variables, $headers);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
