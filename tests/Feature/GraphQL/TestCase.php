<?php

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use MakesGraphQLRequests;

    public function startGraphQL($query, $variables=[], $header=[]){

        $response = $this->postGraphQL([
            'query' => $query,
            'variables' => $variables,
        ],$header);
        $response->assertOk();
        $this->assertNull($response->json('errors'));

    }
}