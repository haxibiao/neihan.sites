<?php

namespace Tests\Feature\GraphQL;

use App\Collection;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Ios1v4Test extends GraphQLTestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'api_token' => str_random(60),
        ]);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testCollectionsQuery
     */
    public function testCollectionsQuery()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/CollectionsQuery.gql');
        $variables = [
            'user_id' => $this->user->id
        ];
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $this->startGraphQL($query, $variables, $userHeaders);

    }
    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testCollectionQuery
     */
    public function testCollectionQuery()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/CollectionQuery.gql');
        $collection = Collection::first();
        $variables = [
            "collection_id" => $collection->id,
        ];
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $this->startGraphQL($query, $variables, $userHeaders);

    }


    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testCreateCollectionMutation
     */
    public function testCreateCollectionMutation()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/CreateCollectionMutation.gql');
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $post = Post::first();
        //创建时添加合集
        $variables = [
            'name' => "测试",
            "collectable_ids" => [ $post->id],
            ];
        $this->startGraphQL($query, $variables, $userHeaders);
        //创建时不添加合集
        $variables = [
            'name' => "测试",
        ];
        $this->startGraphQL($query, $variables, $userHeaders);

    }

    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testEditCollectionMutation
     */
    public function testEditCollectionMutation()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/EditCollectionMutation.gql');
        $collection = Collection::first();
        $variables = [
            "collection_id" => $collection->id,
            "name" => "测试修改"
        ];
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $this->startGraphQL($query, $variables, $userHeaders);

    }

    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testCreatePostContent
     *     //测试带合集的视频采集功能
     */
    public function testCreatePostContent()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/CreatePostContent.gql');
        $collection = Collection::first();
        $variables = [
            "body" => "tangshujuanceshi",
            "qcvod_fileid" => "5285890796669586161",
            "share_link" => "来来来你来当狗！  https://v.douyin.com/JStGhjr/ 复制此链接，打开【抖音短视频】，直接观看视频！",
            "collection_ids" => [ $collection->id],
        ];
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $this->startGraphQL($query, $variables, $userHeaders);

    }

    /**
 * @group  Ios1v4Test
 * @group  Ios1v4Test_testMoveInCollectionsMutation
 *     //测试添加合集
 */
    public function testMoveInCollectionsMutation()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/moveInCollectionsMutation.gql');
        $collection = Collection::first();
        $post = Post::first();
        $variables = [
            "collection_id" => $collection->id,
            "collectable_ids" => [$post->id],
        ];
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $this->startGraphQL($query, $variables, $userHeaders);

    }
    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testMoveOutCollectionsMutation
     *     //测试添加合集
     */
    public function testMoveOutCollectionsMutation()
    {
        $queryIn = file_get_contents(__DIR__ . '/ios1v4/moveInCollectionsMutation.gql');
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $collection = Collection::first();
        //往合集中添加视频
        $post = Post::first();
        $variablesIn = [
            "collection_id" => $collection->id,
            "collectable_ids" => [ $post->id],
        ];
        $this->startGraphQL($queryIn, $variablesIn, $userHeaders);
        //将视频从合集中移除
        $queryOut = file_get_contents(__DIR__ . '/ios1v4/moveOutCollectionsMutation.gql');
        $post = $collection->posts()->first();
        $variablesOut = [
            "collection_id" => $collection->id,
            "collectable_ids" => [ $post->id],
        ];
        $this->startGraphQL($queryOut, $variablesOut, $userHeaders);

    }

    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testSearchCollectionsMutation
     *     //测试添加合集
     */
    public function testSearchCollectionsMutation()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/searchCollectionsMutation.gql');
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $collection = Collection::firstOrCreate([
            'name'    => '测试合集搜索',
            'user_id' => $this->user->id
        ],[
            'type'   => 'posts',
            'status' => Collection::STATUS_ONLINE,
        ]);
        $variables = [
            "query" => '测试',
        ];
        $this->startGraphQL($query, $variables, $userHeaders);

    }

    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testDeleteCollectionMutation
     */
    public function testDeleteCollectionMutation()
    {
        $query = file_get_contents(__DIR__ . '/ios1v4/DeleteCollectionMutation.gql');
        $userHeaders = $this->getRandomUserHeaders($this->user);
        $collection = Collection::first();
        $variables = [
            "id" => $collection->id,
        ];
        $this->startGraphQL($query, $variables, $userHeaders);

    }

    /**
     * @group  Ios1v4Test
     * @group  Ios1v4Test_testUserLikedArticlesQuery
     */
    public function testUserLikedArticlesQuery(){
        $user = User::find(1);
        $query   = file_get_contents(__DIR__ . '/ios1v4/UserLikedArticlesQuery.gql');
        $variables = [
            'user_id' => $user->id,
        ];
        $this->startGraphQL($query, $variables, []);
    }
}
