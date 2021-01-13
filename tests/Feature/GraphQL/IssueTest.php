<?php

namespace Haxibiao\Content\Tests\Feature\GraphQL;

use App\Issue;
use App\User;
use Haxibiao\Breeze\GraphQLTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IssueTest extends GraphQLTestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::inRandomorder()->first();

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /**
     * @group testCreateIssueMutation
     */
    public function testCreateIssueMutation()
    {
        $query     = file_get_contents(__DIR__ . '/Issue/Mutation/createIssueMutation.gql');
        $base64    = $this->getBase64ImageString();
        $headers   = $this->getRandomUserHeaders();
        $variables = [
            "title"      => "创建一个问题",
            "background" => "HelloWorld",
        ];

        $this->runGuestGQL($query, $variables, $headers);

        //创建戴图片的问题
        $variables = [
            "title"       => "创建一个问题",
            "background"  => "HelloWorld",
            'cover_image' => $base64,
        ];

        $this->runGuestGQL($query, $variables, $headers);
    }

    /**
     * @group testSearchIssue
     */
    public function testSearchIssue()
    {

        $query     = file_get_contents(__DIR__ . '/Issue/Query/searchIssueQuery.gql');
        $headers   = $this->getRandomUserHeaders();
        $issue     = Issue::inRandomorder()->first();
        $variables = [
            'query' => str_limit($issue->title, 5),
        ];
        $this->runGuestGQL($query, $variables, $headers);
    }

    /**
     * @group testIssuesQuery
     */
    public function testIssuesQuery()
    {

        $query = file_get_contents(__DIR__ . '/Issue/Query/issuesQuery.gql');

        $variables = [
            'orderBy' => [
                [
                    "order" => "DESC",
                    "field" => "HOT",
                ],
            ],
        ];
        $this->runGuestGQL($query, $variables);
        //默认排序方式
        $variables = [];
        $this->runGuestGQL($query, $variables);

    }
    /**
     * @group testDeleteIssueMutation
     */
    public function testDeleteIssueMutation()
    {
        $query   = file_get_contents(__DIR__ . '/Issue/Mutation/deleteIssue.gql');
        $user    = User::inRandomorder()->first();
        $token   = $user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        //查找当前用户创建的issue
        $args = [
            'user_id' => $user->id,
            'title'   => "i'am a issue",
        ];
        $issue     = Issue::firstOrCreate($args);
        $variables = [
            'issue_id' => $issue->id,
        ];

        $this->runGuestGQL($query, $variables, $headers);
    }

}
