<?php

namespace Tests\Feature\GraphQL;

use App\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

abstract class GraphQLTestCase extends TestCase
{
    use MakesGraphQLRequests;

    public function startGraphQL($query, $variables = [], $header = [])
    {
        $response = $this->postGraphQL([
            'query'     => $query,
            'variables' => $variables,
        ], $header);
        $response->assertOk();

        $this->assertNull($response->json('errors'));
        $this->assertNull($response->json('error'));
        return $response;
    }

    /**
     * @group gql
     */
    public function runGQL($query, $variables = [], $headers = [])
    {
        $this->runGuestGQL(
            $query,
            $variables,
            array_merge($headers, $this->getRandomUserHeaders())
        );
    }

    /**
     * @group gql
     */
    public function runGuestGQL($query, $variables = [], $headers = [])
    {
        //主要测试新版本
        $headers = array_merge($headers, ['version' => '2.7.0']);

        $response = $this->postGraphQL([
            'query'     => $query,
            'variables' => $variables,
        ], $headers);
        $response->assertOk();
        $this->assertNull($response->json('errors'));
        return $response;
    }

    /**
     * @group gql
     */
    public function getRandomUserHeaders()
    {
        $user  = $this->getRandomUser();
        $token = $user->api_token;

        $headers = [
            'token'         => $token,
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        return $headers;
    }

    /**
     * @group gql
     */
    public function getRandomUser()
    {
        //从最近的新用户中随机找一个，UT侧重新用户的体验问题
        $user = User::latest('id')->take(100)->get()->random();
        return $user;
    }

    public function getHeaders($user): array
    {
        $token   = $user->api_token;
        $headers = [
            'token'         => $token,
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        return $headers;
    }
}
