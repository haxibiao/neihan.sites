<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
   	/**
   	 * @Desc     用户关注
   	 * @Author   czg
   	 * @DateTime 2018-07-15
   	 * @return   [type]     [description]
   	 */
    public function testUserFollows(){
    	$user = User::inRandomOrder()
            ->first();

        $followFilter = array_random(['USER','CATEGORY','COLLECTION', 'USER_CATEGORY']);

        $query = <<<STR
        query userFollows(\$user_id: Int, \$limit: Int, \$offset: Int, \$filter: FollowFilter) {
		    follows(user_id: \$user_id, filter: \$filter, offset: \$offset, limit: \$limit) {
		        id
		        followed_id
		        followed_type
		        name
		        avatar
		        latest_article_title
		        dynamic_msg
		    }
		}
STR;
		$variables = <<<STR
        {
          "user_id":  $user->id,
          "filter" :  "$followFilter"
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'       => $query,
                'variables'   => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    } 

    /**
     * @Desc     用户个人资源统计
     * @Author   czg
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testUserResourceCountQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query userResourceCountQuery(\$id: Int!) {
		    user(id: \$id) {
		        count_likes
		        count_words
		        count_articles
		        count_follows
		        count_followers
		        count_followings
		        count_drafts
		        count_favorites
		        count_categories
		        count_collections
		    }
		}
STR;
		$variables = <<<STR
        {
          "id":  $user->id
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'       => $query,
                'variables'   => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    } 
    /**
     * @Desc     浏览记录
     * @Author   czg
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testVisitsQuery(){
    	$visitor = User::inRandomOrder()
            ->first();
        $visitFilter = array_random(['EARLY','TODAY']);

        $query = <<<STR
        query visitsQuery(\$visit: VisitFilter) {
		    visits(offset: 0, limit: 15, filter: \$visit) {
		        time_ago
		        type
		        visited {
		            id
		            title
		        }
		    }
		}	
STR;
		$variables = <<<STR
        {
          "visit":  "$visitFilter"
        }
STR;
        $response = $this->actingAs($visitor)
        	->json("POST", "/graphql", [
                'query'       => $query,
                'variables'   => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    } 
    /**
     * @Desc     阅读量
     * @Author   czg
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testMyReadsQuery(){
    	$visitor = User::inRandomOrder()
            ->first();

        $query = <<<STR
        query myReadsQuery {
		    user {
		        today_read_num
		        today_read_rate
		    }
		}	
STR;
        $response = $this->actingAs($visitor)
        	->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }



}