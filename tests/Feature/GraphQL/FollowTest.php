<?php

namespace Tests\Feature\GraphQL;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowTest extends GraphQLTestCase
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

    /**
     * @group  testFollowMutation
     */
    public function testFollowMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/Follow/Mutation/followMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        // 随机获取一个幸运用户
        $user = User::inRandomOrder()->first();
        
        $variables = [
            'type' => 'users',
            'id'   => $user->id,
        ];

        $this->runGQL($query, $variables, $headers);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
