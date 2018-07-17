<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Category;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
/**
 * 该测试用例最后的更新时间为2018年7月17日19时
 */
class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @Desc     获取未读消息数
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testUnreadsQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query unreadsQuery {
		    user {
		        id
		        unread_comments
		        unread_likes
		        unread_follows
		        unread_requests
		        unread_tips
		        unread_others
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     新投稿请求
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testNewRequestedCategoriesQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query newRequestedCategoriesQuery {
		    user {
		        id
		        name
		        categories(filter: REQUESTED) {
		            id
		            name
		            logo
		            new_requests
		            latestArticle {
		                id
		                title
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     评论提醒
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCommentNotificationQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query commentNotificationQuery(\$offset: Int) {
		    user {
		        id
		        name
		        notifications(type: ARTICLE_COMMENTED, offset: \$offset) {
		            id
		            type
		            user {
		                id
		                name
		                avatar
		            }
		            comment {
		                id
		                body
		                likes
		                liked
		                time_ago
		                commentable_id
		                user {
		                    id
		                    avatar
		                    name
		                }
		            }
		            article {
		                id
		                type
		                title
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     喜欢与赞消息通知
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testLikeNotificationsQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query likeNotificationsQuery(\$offset: Int) {
		    user {
		        id
		        name
		        notifications(type: GROUP_LIKES, offset: \$offset) {
		            id
		            type
		            article {
		                id
		                type
		                title
		                time_ago
		            }
		            comment {
		                id
		                body
		                time_ago
		            }
		            user {
		                id
		                name
		                avatar
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

    /**
     * @Desc     关注通知
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testFollowersNotificationsQuery(){
    	$user = User::inRandomOrder()
            ->first();
        $query = <<<STR
        query followersNotificationsQuery(\$offset: Int) {
		    user {
		        id
		        name
		        notifications(type: USER_FOLLOWED, offset: \$offset) {
		            id
		            type
		            time_ago
		            user {
		                id
		                name
		                avatar
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     专题投稿详情
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCategoryPendingArticlesQuery(){
    	$category = Category::inRandomOrder()
            ->first();
        $admin = $category->admins->random();
        //ALL全部投稿请求，PEDING：未处理的投稿请求
        $filter = array_random(['ALL', 'PEDING']);
        
        $query = <<<STR
        query categoryPendingArticlesQuery(\$category_id: Int!, \$filter: ArticleFilter) {
		    category(id: \$category_id) {
		        id
		        name
		        articles(filter: \$filter) {
		            id
		            type
		            title
		            user {
		                id
		                name
		                avatar
		            }
		            pivot_time_ago
		            pivot_status
		            pivot_category {
		                id
		                name
		            }
		        }
		    }
		}
STR;
		$variables = <<<STR
        {
          "category_id"    : $category->id,
          "filter"         : "$filter"
        }
STR;
        $response = $this->actingAs($admin)
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
     * @Desc     [desc]
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testPendingArticlesQuery(){
    	$user = User::inRandomOrder()
            ->first();
        
        $query = <<<STR
        query pendingArticlesQuery {
		    user {
		        id
		        articles(filter: NEW_REQUESTED) {
		            id
		            type
		            title
		            user {
		                id
		                name
		                avatar
		            }
		            pivot_time_ago
		            pivot_status
		            pivot_category {
		                id
		                name
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

    /**
     * @Desc     用户打赏通知
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testTipNotificationsQuery(){
    	$user = User::inRandomOrder()
            ->first();
        
        $query = <<<STR
        query tipNotificationsQuery(\$offset: Int) {
		    user {
		        id
		        notifications(type: ARTICLE_TIPED, offset: \$offset) {
		            id
		            type
		            time_ago
		            user {
		                id
		                name
		                avatar
		            }
		            article {
		                id
		                type
		                title
		            }
		            tip {
		                message
		                amount
		                user {
		                    id
		                    name
		                }
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     其他类型的消息通知
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testOtherNotificationsQuery(){
    	$user = User::inRandomOrder()
            ->first();
        
        $query = <<<STR
        query otherNotificationsQuery(\$offset: Int) {
		    user {
		        id
		        notifications(type: GROUP_OTHERS, offset: \$offset) {
		            id
		            type
		            read_at
		            category {
		                id
		                name
		            }
		            user {
		                id
		                name
		                avatar
		            }
		            collection {
		                id
		                name
		            }
		            article {
		                id
		                type
		                title
		            }
		        }
		    }
		}
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'       => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
}