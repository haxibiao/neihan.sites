<?php

namespace Tests\Feature\GraphQL;

use App\User;
use haxibiao\content\Post;
use haxibiao\media\Video;

class PostTest extends GraphQLTestCase
{

    protected $user;

    protected $post;

    protected $video;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->video = factory(Video::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->post = factory(Post::class)->create([
            'user_id'  => $this->user->id,
            'video_id' => $this->video->id,
        ]);

    }

    /**
     * 用户发布视频动态
     *
     * @group post
     */
    public function testUserPostsQuery()
    {
        $query     = file_get_contents(__DIR__ . '/Post/UserPostsQuery.gql');
        $variables = [
            "user_id" => $this->user->id,
        ];
        $this->runGQL($query, $variables);
    }

    /**
     * 推荐视频列表
     *
     * @group post
     */
    public function testRecommendPostsQuery()
    {
        $query     = file_get_contents(__DIR__ . '/Post/RecommendPostsQuery.gql');
        $variables = [];
        $this->runGQL($query, $variables);
    }

    /**
     * 视频详情
     *
     * @group post
     */
    public function testPostQuery()
    {
        $query = file_get_contents(__DIR__ . '/Post/PostQuery.gql');

        $variables = [
            'id' => $this->post->id,
        ];

        $this->runGQL($query, $variables);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();
        $this->post->forceDelete();
        $this->video->forceDelete();
        parent::tearDown();
    }

}
