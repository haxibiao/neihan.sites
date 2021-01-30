<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\Comment;
use App\Feedback;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\GraphQL\TestCase;

class CommentTest extends GraphQLTestCase
{
    use DatabaseTransactions;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make([
            'api_token' => str_random(60),
        ]);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * @group  testAddCommentMutation
     */
    public function testCreateCommentMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/Comment/Mutation/createCommentMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        //情形一:评论动态
        $comment = Post::where('status', 0)->inRandomOrder()->first();

        $variables = [
            'type' => 'posts', //这个还是文章类型
            'id'   => $comment->id,
            'content'             => '评论动态',
        ];

        $this->runGQL($query, $variables, $headers);

        //情形二:评论评论
        $comment   = Comment::inRandomOrder()->first();
        $variables = [
            'type' => 'comments',
            'id'   => $comment->id,
            'content'             => '评论评论',
        ];

        $this->runGQL($query, $variables, $headers);

        //情形二:评论反馈
        $comment   = Feedback::inRandomOrder()->first();
        $variables = [
            'type' => 'feedbacks',
            'id'   => $comment->id,
            'content'             => '评论反馈',
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * @group  testDeleteCommentMutation
     */
    public function testDeleteCommentMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/Comment/Mutation/deleteCommentMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $comment = Comment::inRandomOrder()->first();

        $variables = [
            'id' => $comment->id,
        ];

        $this->runGQL($query, $variables, $headers);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
