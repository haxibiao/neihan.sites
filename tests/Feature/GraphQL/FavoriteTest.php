<?php

namespace Tests\Feature\GraphQL;

use App\User;

class FavoriteTest extends TestCase
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

    //FIXME: 接口没写
    // public function testfavoriteArticleMutation()
    // {
    //     $query = file_get_contents(__DIR__ . '/Favorite/Mutation/favoriteArticleMutation.gql');

    //     $article = Article::inRandomOrder()->first();

    //     //添加favorite
    //     $variables = [
    //         'article_id' => $article->id,
    //         'undo'       => true,
    //     ];

    //     //删除favorite
    //     $variables = [
    //         'article_id' => $article->id,
    //         'undo'       => false,
    //     ];

    //     $this->startGraphQL($query, $variables);
    // }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testfavoritedArticlesQuery()
    {
        $query = file_get_contents(__DIR__ . '/Favorite/Query/favoritedArticlesQuery.gql');

        $token = $this->user->api_token;

        $user = User::inRandomOrder()->first();

        $variables = [
            'user_id' => $user->id,
        ];

        $this->startGraphQL($query, $variables);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
