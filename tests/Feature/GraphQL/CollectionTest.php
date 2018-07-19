<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Collection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
/**
 * 该测试用例最后的更新时间为2018年7月17日19时
 */
class CollectionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @Desc     查询专辑与文章
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCollectionQuery(){
        
        $order      = array_random(['LATEST','COMMENTED','HOT']);
        $collection = Collection::inRandomOrder()->first();

        $query = <<<STR
        query collectionQuery(\$id: Int!, \$order: ArticleOrder!, \$offset: Int) {
			collection(id: \$id) {
				id
				name
				logo
				user {
					id
					name
					avatar
					count_words
					count_articles
					count_likes
				}
				followed
				count_articles
				count_follows
			}
			articles(collection_id: \$id, order: \$order, offset: \$offset) {
				id
				type
				title
				description
				has_image
				images
				cover
				time_ago
				user {
					id
					name
					avatar
				}
				hits
				count_replies
				count_tips
				count_likes
			}
		}
STR;
        $variables = <<<STR
        {
          "id"        : $collection->id,
          "order"     : "$order"
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     查询关注该文集的人
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCollectionFollowersQuery(){
        $collection = Collection::inRandomOrder()->first();

        $query = <<<STR
        query collectionFollowersQuery(\$id: Int!, \$offset: Int) {
			users(collection_id: \$id, offset: \$offset) {
				id
				name
				avatar
			}
		}
STR;
        $variables = <<<STR
        {
          "id"        : $collection->id
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     创建专题
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCreateCollectionMutation(){
        $user = User::inRandomOrder()->first();

        $query = <<<STR
        mutation createCollectionMutation(\$name: String!) {
			createCollection(name: \$name) {
				id
				name
			}
		}
STR;
        $variables = <<<STR
        {
          "name"        : "测试名"
        }
STR;
        $response = $this->actingAs($user)
        	->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     编辑专题
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testEditCollectionMutation(){
        $collection = Collection::inRandomOrder()->first();
        $user = $collection->user;

        $query = <<<STR
        mutation editCollectionMutation(\$id: Int!, \$name: String!) {
			editCollection(id: \$id, name: \$name) {
				id
				name
			}
		}
STR;
        $variables = <<<STR
        {
          "id"			: $collection->id,
          "name"        : "测试名"
        }
STR;
        $response = $this->actingAs($user)
        	->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     删除文集
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testDeleteCollectionMutation(){
        $collection = Collection::whereCount(0)
            ->inRandomOrder()
            ->first();
        $user = $collection->user;

        $query = <<<STR
        mutation deleteCollectionMutation(\$id: Int!) {
			deleteCollection(id: \$id) {
				id
				name
			}
		}
STR;
        $variables = <<<STR
        {
          "id"			: $collection->id
        }
STR;
        $response = $this->actingAs($user)
        	->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

}