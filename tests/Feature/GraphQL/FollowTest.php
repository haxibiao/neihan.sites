<?php

namespace Tests\Feature\GraphQL;

use App\Article;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FollowTest extends GraphQLTestCase
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

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
