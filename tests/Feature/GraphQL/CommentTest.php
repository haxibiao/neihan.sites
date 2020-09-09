<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\Comment;
use App\Feedback;
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
        $this->user = factory(User::class)->create([
            'api_token' => str_random(60),
        ]);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * @group  testAddCommentMutation
     */
    public function testAddCommentMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/Comment/Mutation/addCommentMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        //情形一:评论动态
        $comment = Article::where('type', 'post')->inRandomOrder()->first();

        $variables = [
            'commentable_type' => 'articles', //这个还是文章类型
            'commentable_id'   => $comment->id,
            'body'             => '评论动态',
        ];

        $this->runGuestGQL($query, $variables, $headers);

        //情形二:评论评论
        $comment   = Comment::inRandomOrder()->first();
        $variables = [
            'commentable_type' => 'comments',
            'commentable_id'   => $comment->id,
            'body'             => '评论评论',
        ];

        $this->runGuestGQL($query, $variables, $headers);

        //情形二:评论评论
        $comment   = Feedback::inRandomOrder()->first();
        $variables = [
            'commentable_type' => 'feedbacks',
            'commentable_id'   => $comment->id,
            'body'             => '评论评论',
        ];
        $this->runGuestGQL($query, $variables, $headers);
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

        $this->runGuestGQL($query, $variables, $headers);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  testCommentRepliesQuery
     */
    public function testCommentRepliesQuery()
    {
        $query     = file_get_contents(__DIR__ . '/Comment/Query/commentRepliesQuery.gql');
        $comment   = Comment::where('commentable_type', 'comments')->first();
        $variables = [
            'id' => $comment->commentable_id,
        ];
        $this->runGuestGQL($query, $variables);
    }
    /**
     * @group  testCommentsQuery
     */
    public function testCommentsQuery()
    {
        $query = file_get_contents(__DIR__ . '/Comment/Query/commentsQuery.gql');

        $comment = Comment::where('commentable_type', 'articles')->first();

        $variables = [
            'commentable_id'   => $comment->commentable_id,
            'commentable_type' => 'articles',
        ];
        $this->runGuestGQL($query, $variables);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
