<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\User;
use App\Video;
use Database\Factories\CommentFactory;
use Database\Factories\PostFactory;
use Database\Factories\QuestionFactory;
use Database\Factories\VideoFactory;

class LikeTest extends GraphQLTestCase
{

    protected $u1, $article, $comment, $video, $post, $question;

    // 测试开始调用方法
    protected function setUp(): void
    {
        parent::setUp();
        $this->u1 = User::factory()->make();
        $this->article = Article::factory()->make();
        $this->comment = CommentFactory::new ()->make();
        $this->video = VideoFactory::new ()->make();
        $this->post = PostFactory::new ()->make();
        $this->question = QuestionFactory::new ()->make();
    }

    // 测试结束调用方法
    protected function tearDown(): void
    {
        $this->u1->forceDelete();
        $this->article->forceDelete();
        $this->comment->forceDelete();
        $this->video->forceDelete();
        $this->post->forceDelete();
        $this->question->forceDelete();

        parent::tearDown();
    }

    /**
     * 测试 Like Article
     *
     * @return void
     */
    public function testLikeArticle()
    {
        $token = $this->u1->api_token;
        $article = $this->article;
        $article_id = $article->id;

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

        $article->forceDelete();
    }

    public function testUndoLikeArticle()
    {
        $token = $this->u1->api_token;
        $article = $this->article;
        $article_id = $article->id;

        // printf($article_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $article_id,
            'type' => 'articles',
            'undo' => false,
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 测试 Like Comment
     *
     * @return void
     */
    public function testLikeComment()
    {
        $token = $this->u1->api_token;
        $comment = $this->comment;
        $comment_id = $comment->id;

        // printf($comment_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $comment_id,
            'type' => 'comments',
            'undo' => true,
        ];
        $this->runGQL($query, $variables, $headers);

    }
    public function testUndoLikeComment()
    {
        $token = $this->u1->api_token;
        $comment = $this->comment;
        $comment_id = $comment->id;

        // printf($comment_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $comment_id,
            'type' => 'comments',
            'undo' => false,
        ];
        $this->runGQL($query, $variables, $headers);

    }

    /**
     * 测试 Like Video
     *
     * @return void
     */
    public function testLikeVideo()
    {
        $token = $this->u1->api_token;
        $video = $this->video;
        $video_id = $video->id;

        // printf($video_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $video_id,
            'type' => 'videos',
            'undo' => true,
        ];
        $this->runGQL($query, $variables, $headers);

    }
    public function testUndoLikeVideo()
    {
        $token = $this->u1->api_token;
        $video = $this->video;
        $video_id = $video->id;

        // printf($video_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $video_id,
            'type' => 'videos',
            'undo' => false,
        ];
        $this->runGQL($query, $variables, $headers);

    }

    /**
     * 测试 Like Post
     *
     * @return void
     */
    public function testLikePost()
    {
        $token = $this->u1->api_token;
        $post = $this->post;
        $post_id = $post->id;

        // printf($post_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $post_id,
            'type' => 'posts',
            'undo' => true,
        ];
        $this->runGQL($query, $variables, $headers);
    }
    public function testUndoLikePost()
    {
        $token = $this->u1->api_token;
        $post = $this->post;
        $post_id = $post->id;

        // printf($post_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $post_id,
            'type' => 'posts',
            'undo' => false,
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 测试 Like question
     *
     * @return void
     */
    public function testLikeQuestion()
    {
        $token = $this->u1->api_token;
        $question = $this->question;
        $question_id = $question->id;

        // printf($question_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $question_id,
            'type' => 'questions',
            'undo' => true,
        ];
        $this->runGQL($query, $variables, $headers);
    }

    public function testLikeUndoQuestion()
    {
        $token = $this->u1->api_token;
        $question = $this->question;
        $question_id = $question->id;

        // printf($question_id);

        $query = file_get_contents(__DIR__ . '/Like/Mutation/toggleLikeMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $variables = [
            'id' => $question_id,
            'type' => 'questions',
            'undo' => false,
        ];
        $this->runGQL($query, $variables, $headers);
    }

}
