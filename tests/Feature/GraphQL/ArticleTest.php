<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\User;
use App\Video;
use Illuminate\Http\UploadedFile;

class ArticleTest extends TestCase
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
    public function testCreatePostMutation()
    {

        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/Article/Mutation/createPostMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $image  = UploadedFile::fake()->image('photo.jpg');
        $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));

        $article = Article::whereType('video')->first();
        $video   = $article->video;

        //情形1:创建视频动态
        $variables = [
            'video_id' => $video->id,
            'body'     => '测试创建创建视频动态',
            'type'     => 'POST',
        ];
        $this->startGraphQL($query, $variables, $headers);

        //情形2:创建带图动态
        $variables = [
            'images'       => [$base64],
            'body'         => '测试创建带图动态?',
            'type'         => 'POST',
            'category_ids' => [2],
        ];
        $this->startGraphQL($query, $variables, $headers);

        //情形3:创建视频问答
        // $variables = [
        //     'video_id'   => $video->id,
        //     'body'       => '可以上传Base64的图片啦？',
        //     'type'       => 'ISSUE',
        //     'issueInput' => [
        //         'gold' => 30,
        //     ],
        // ];
        // $this->startGraphQL($query, $variables, $headers);

        // //情形4:创建带图问答
        // $variables = [
        //     'body'       => '可以上传Base64的图片啦？',
        //     'type'       => 'ISSUE',
        //     'images'     => $base64,
        //     'issueInput' => [
        //         'gold' => 30,
        //     ],
        // ];
        $this->startGraphQL($query, $variables, $headers);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testArticleQuery()
    {
        $query = file_get_contents(__DIR__ . '/Article/Query/articleQuery.gql');

        // $videoPost = Article::whereStatus(1)
        //     ->whereType('post')
        //     ->whereNotNull('video_id')->first();

        $videoPost = Article::inRandomOrder()->first();

        $variableses = [
            'id' => $videoPost->id,
        ];
        $this->startGraphQL($query, $variableses);
    }

    public function testArticlesQuery()
    {
        $query = file_get_contents(__DIR__ . '/Article/Query/articlesQuery.gql');

        $variableses = [
            'page' => random_int(1, 10),
        ];
        $this->startGraphQL($query, $variableses);
    }

    public function testUserArticlesQuery()
    {
        $query = file_get_contents(__DIR__ . '/Article/Query/userArticlesQuery.gql');

        $user = User::inRandomOrder()->first();

        $variableses = [
            'user_id' => $user->id,
            'page'    => random_int(1, 10),
        ];
        $this->startGraphQL($query, $variableses);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
