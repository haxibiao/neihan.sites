<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\Post;
use App\Question;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoriteTest extends GraphQLTestCase
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

    public function testfavoriteArticleMutation()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Mutation/favoriteMutation.gql');

        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $article = Article::inRandomOrder()->first();

        if (!$article) {
            echo PHP_EOL . PHP_EOL . "By: \033[31mArticle Content for null!!!!\033[0m\n" . PHP_EOL;
            return;
        }

        $variables = [
            'id'   => $article->id,
            'type' => "articles",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    public function testfavoritePostMutation()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Mutation/favoriteMutation.gql');

        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $post = Post::inRandomOrder()->first();

        if (!$post) {
            echo PHP_EOL . PHP_EOL . "By: \033[31mPost Content for null!!!!\033[0m\n" . PHP_EOL;
            return;
        }

        $variables = [
            'id'   => $post->id,
            'type' => "posts",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    public function testfavoritequestionMutation()
    {
        $query   = file_get_contents(__DIR__ . '/Favorite/Mutation/favoriteMutation.gql');
        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $question = Question::inRandomOrder()->first();

        if (!$question) {
            echo PHP_EOL . PHP_EOL . "By: \033[31mQuestion Content for null!!!!\033[0m\n" . PHP_EOL;
            return;
        }

        $variables = [
            'id'   => $question->id,
            'type' => "questions",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testfavoritedPostsQuery()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Query/favoritedQuery.gql');

        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $variables = [
            'type' => "posts",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    public function testfavoritedQuestionsQuery()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Query/favoritedQuery.gql');

        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $variables = [
            'type' => "questions",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    public function testfavoritedArticlesQuery()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Query/favoritedQuery.gql');

        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $variables = [
            'type' => "articles",
        ];

        $this->runGQL($query, $variables, $headers);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
