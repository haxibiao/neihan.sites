<?php

namespace Tests\Feature\GraphQL;

use App\Chat;
use App\User;
use Illuminate\Support\Str;

class ChatTest extends TestCase
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
    public function testcreateChatMutation()
    {
        $query = file_get_contents(__DIR__ . '/Chat/Mutation/createChatMutation.gql');

        $token = $this->user->api_token;

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $user = User::inRandomOrder()->first();

        $variables = [
            'id' => $user->id,
        ];

        $this->startGraphQL($query, $variables, $headers);
    }

    public function testsendMessageMutation()
    {
        $query = file_get_contents(__DIR__ . '/Chat/Mutation/sendMessageMutation.gql');

        $chat      = Chat::inRandomOrder()->first();
        $user      = $chat->users->get(0);
        $variables = [
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'message' => Str::random(7),
        ];

        $this->startGraphQL($query, $variables);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testchatsQuery()
    {
        $query = file_get_contents(__DIR__ . '/Chat/Query/chatsQuery.gql');

        $token = $this->user->api_token;

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $chat = Chat::inRandomOrder()->first();
        $user = $chat->users->get(0);

        $variables = [
            'user_id' => $user->id,
        ];

        $this->startGraphQL($query, $variables, $headers);
    }

    public function testmessageQuery()
    {
        $query = file_get_contents(__DIR__ . '/Chat/Query/messagesQuery.gql');

        $chat = Chat::inRandomOrder()->first();

        $variables = [
            'chat_id' => $chat->id,
        ];

        $this->startGraphQL($query, $variables);
    }

    protected function tearDown(): void
    {
        $this->user->forceDelete();

        parent::tearDown();
    }
}
