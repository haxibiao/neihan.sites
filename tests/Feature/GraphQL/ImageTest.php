<?php

namespace Tests\Feature\GraphQL;

class ImageTest extends GraphQLTestCase
{

    public function testUploadImageMutation()
    {
        $token   = $this->getRandomUser()->api_token;
        $query   = file_get_contents(__DIR__ . '/image/UploadImageMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'image' => [
                $this->getBase64ImageString(),
            ],
        ];
        $this->runGQL($query, $variables, $headers);
    }

}
