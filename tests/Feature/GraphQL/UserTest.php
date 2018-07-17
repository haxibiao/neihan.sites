<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Comment;
use App\Article;
use App\Category;
use App\Collection;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase {
	use DatabaseTransactions;
	/**
	 * @Desc     用户关注
	 * @Author   czg
	 * @DateTime 2018-07-15
	 * @return   [type]     [description]
	 */
	public function testUserFollows() {
		$user = User::inRandomOrder()
			->first();

		$followFilter = array_random(['USER', 'CATEGORY', 'COLLECTION', 'USER_CATEGORY']);

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
			'query' => $query,
			'variables' => $variables,
		]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户个人资源统计
	 * @Author   czg
	 * @DateTime 2018-07-15
	 * @return   [type]     [description]
	 */
	public function testUserResourceCountQuery() {
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
			'query' => $query,
			'variables' => $variables,
		]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     浏览记录
	 * @Author   czg
	 * @DateTime 2018-07-15
	 * @return   [type]     [description]
	 */
	public function testVisitsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$visitFilter = array_random(['EARLY', 'TODAY']);

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
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     阅读量
	 * @Author   czg
	 * @DateTime 2018-07-15
	 * @return   [type]     [description]
	 */
	public function testMyReadsQuery() {
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
				'query' => $query,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
    /**
     * @Desc     举报用户
     * @Author   czg
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testReportUserMutation(){
        $users = User::inRandomOrder()
            ->take(2)
            ->get();
        $visitor = $users->last();
        $author  = $users->first();
        $type    = array_random(['广告或垃圾信息','抄袭或转载','其他']);

        $query = <<<STR
        mutation reportUserMutation(\$id: Int!, \$type: String, \$reason: String) {
            reportUser(id: \$id, type: \$type, reason: \$reason) {
                id
            }
        }         
STR;
        $variables = <<<STR
        {
          "id"     : $author->id,
          "type"    : "$type",
          "reason"  : "balbala"
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
     * @Desc     举报用户评论
     * @Author   czg
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testReportUserCommentMutation(){
        $users = User::inRandomOrder()
            ->take(2)
            ->get();
        $visitor = $users->last();
        $author  = $users->first();
        $type    = array_random(['广告或垃圾信息','抄袭或转载','其他']);
        $comment = Comment::inRandomOrder()
            ->first();

        $query = <<<STR
        mutation reportUserCommentMutation(\$id: Int!, \$type: String, \$reason: String, \$comment_id: Int!) {
            reportUser(id: \$id, type: \$type, reason: \$reason, comment_id: \$comment_id) {
                id
            }
        }        
STR;
        $variables = <<<STR
        {
          "id"        : $author->id,
          "type"      : "$type",
          "reason"    : "balbala",
          "comment_id": $comment->id
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
     * @Desc     拉黑用户
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testBlockUserMutation(){

        $users = User::inRandomOrder()
            ->take(2)
            ->get();
        $visitor = $users->first();
        $block_user  = $users->last(); 

        $query = <<<STR
        mutation blockUserMutation(\$user_id: Int!) {
            blockUser(user_id: \$user_id) {
                id
                name
                avatar
            }
        }
STR;
        $variables = <<<STR
        {
          "user_id"        : $block_user->id
        }
STR;
        $response = $this->actingAs($visitor)
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
     * @Desc     拉黑的用户
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testBlockedUsersQuery(){

        $visitor = User::inRandomOrder()
            ->first();

        $query = <<<STR
        query blockedUsersQuery {
            user {
                id
                blockedUsers {
                    id
                    name
                    avatar
                }
            }
        }
STR;
        $response = $this->actingAs($visitor)
            ->json("POST", "/graphql", [
                'query'         => $query
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

    /**
     * @Desc     用户好友列表
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testUserFriendsQuery(){

        $visitor = User::inRandomOrder()
            ->first();

        $query = <<<STR
        query userFriendsQuery(\$offset: Int) {
            user {
                id
                friends(offset: \$offset) {
                    id
                    name
                    avatar
                }
            }
        }
STR;
        $response = $this->actingAs($visitor)
            ->json("POST", "/graphql", [
                'query'         => $query
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

    /**
     * @Desc     移除文章
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testRemoveArticleMutation(){

        $article = Article::inRandomOrder()
            ->first();
        $author  = $article->user;

        $query = <<<STR
        mutation removeArticleMutation(\$id: Int!) {
            removeArticle(id: \$id) {
                id
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
        }
STR;
        $response = $this->actingAs($author)
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
     * @Desc     恢复文章
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testRestoreArticleMutation(){

        $article = Article::inRandomOrder()
            ->first();
        $author  = $article->user; 

        $query = <<<STR
        mutation restoreArticleMutation(\$id: Int!) {
            restoreArticle(id: \$id) {
                id
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
        }
STR;
        $response = $this->actingAs($author)
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
     * @Desc     删除文章
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testDeleteArticleMutation(){

        $article = Article::inRandomOrder()
            ->first();
        $author  = $article->user; 

        $query = <<<STR
        mutation deleteArticleMutation(\$id: Int!) {
            deleteArticle(id: \$id) {
                id
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
        }
STR;
        $response = $this->actingAs($author)
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
     * @Desc     用户删除的文章
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testUserTrashQuery(){

        $user = User::inRandomOrder()
            ->first();

        $query = <<<STR
        query userTrashQuery(\$offset: Int) {
            user {
                id
                articles(filter: TRASH, offset: \$offset) {
                    id
                    title
                    updated_at
                }
            }
        }
STR;
        $response = $this->actingAs($user)
            ->json("POST", "/graphql", [
                'query'         => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     注册，登录
     * @Author   czg
     * @DateTime 2018-07-16
     * @return   [type]     [description]
     */
    public function testSignInAndUpMutation(){
        $name = 'test6666666';
        $password  = 'test6666666';
        $email     = 'test6666666@haxibiao.com'; 
        $regist_query= <<<STR
        mutation signUpMutation(\$email: String!, \$password: String!, \$name: String!) {
            signUp(email: \$email, password: \$password, name: \$name) {
                id
                name
                email
                avatar
                token
                introduction
                count_words
                count_articles
                count_likes
                count_follows
                count_followers
                count_followings
                count_drafts
                count_favorites
                count_categories
                count_collections
                balance
            }
        }
STR;
        $login_query = <<<STR
        mutation signInMutation(\$email: String!, \$password: String!) {
          signIn(email: \$email, password: \$password) {
              id
              name
              email
              avatar
              token
              introduction
              count_words
              count_articles
              count_likes
              count_follows
              count_followers
              count_followings
              count_drafts
              count_favorites
              count_categories
              count_collections
              balance
              error
          }
      }
STR;
        $regist_variables= <<<STR
        {
          "email"   : "$email",
          "name"    : "$name",
          "password": "$password"
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $regist_query,
                'variables'     => $regist_variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);

        $login_variables= <<<STR
        {
          "email"   : "$email",
          "password": "$password"
        }
STR;
        
        $response = $this->json("POST", "/graphql", [
                'query'         => $login_query,
                'variables'     => $login_variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
	 * @Desc     更新用户名
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUpdateUserName() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        mutation updateUserNameMutation(\$name: String!) {
            updateUserName(name: \$name) {
                id
                name
            }
        }
STR;
		$variables = <<<STR
        {
          "name":  "lyy"
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     更新用户介绍
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUpdateUserIntroduction() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        mutation updateUserIntroductionMutation(\$introduction: String!) {
                updateUserIntroduction(introduction: \$introduction) {
                id
                introduction
            }
        }
STR;
		$variables = <<<STR
        {
          "introduction":  "balabala"
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     更新用户密码
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUpdateUserPassword() {
		//
		$user = new User();
		$user->password = bcrypt('123123123');
		$user->name = 123;
		$user->email = 13;
		$user->save();

		$query = <<<STR
        mutation updateUserPasswordMutation(\$oldpassword: String!, \$password: String!) {
                updateUserPassword(oldpassword: \$oldpassword, password: \$password) {
                id
                name
            }
        }
STR;
		$variables = <<<STR
        {
          "oldpassword": "123123123",
          "password": "ghmyjtyh"
        }
STR;
		$response = $this->actingAs($user)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     推荐作者
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testRecommendAuthorsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
      query  recommendAuthors(\$offset: Int) {
            users(filter: RECOMMEND, offset: \$offset) {
                id
                name
                avatar
                followed_status
                followings(offset: 1) {
                    followed_id
                    name
                    avatar
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户主页
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserDetailQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query userDetailQuery(\$id: Int!, \$offset: Int) {
            user(id: \$id) {
                id
                name
                avatar
                introduction
                count_words
                count_articles
                count_likes
                count_follows
                count_followers
                count_followings
                count_drafts
                count_favorites
                count_categories
                count_collections
                followed_status
            }
            articles(offset: \$offset, user_id: \$id) {
                id
                type
                title
                has_image
                images
                cover
                description
                time_ago
                user {
                    id
                    name
                    avatar
                }
                category {
                    id
                    name
                    logo
                }
                liked
                hits
                count_tips
                count_likes
                count_replies
            }
        }
STR;
		$variables = <<<STR
        {
            "offset"    : $offset,
            "id"      	: $visitor->id,
            "user_id" 	: $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户公开文章
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserArticlesQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query userArticlesQuery(\$offset: Int, \$user_id: Int!) {
                articles(offset: \$offset, user_id: \$user_id) {
                    id
                    type
                    title
                    has_image
                    description
                    images
                    cover
                    updated_at
                    time_ago
                    hits
                    count_likes
                    count_replies
                    count_tips
                    user {
                        id
                        name
                        avatar
                    }
                    collection {
                        id
                        name
                    }
                }
            }
STR;
		$variables = <<<STR
        {
            "offset" 	: $offset,
            "user_id" 	: $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户草稿
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testDraftsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query draftsQuery(\$offset: Int) {
            user {
                id
                articles(offset: \$offset, filter: DRAFTS) {
                    id
                    type
                    title
                    description
                    updated_at
                    has_image
                    images
                    cover
                    time_ago
                    collection {
                        id
                        name
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户喜欢的文章
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserLikedArticlesQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query userLikedArticlesQuery(\$offset: Int, \$user_id: Int!) {
            articles(offset: \$offset, user_id: \$user_id, filter: LIKED) {
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
                count_likes
                count_replies
                count_tips
            }
        }
STR;
		$variables = <<<STR
        {
        "offset" : $offset,
        "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     删除的文章
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testRemovedArticlesQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query removedArticlesQuery {
            user {
                articles(filter: TRASH) {
                    id
                    type
                    title
                    time_ago
                }
            }
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     收藏的文章
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testFavoritedArticleQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query favoritedArticlesQuery(\$offset: Int) {
            user {
                id
                articles(offset: \$offset, filter: FAVED) {
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
                    }
                    hits
                    count_likes
                    count_replies
                    count_tips
                }
            }
        }
STR;
		$variables = <<<STR
        {
        "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户专题
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserCategoriesQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query userCategoriesQuery(\$user_id: Int!) {
            categories(user_id: \$user_id, limit: 100) {
                id
                name
                logo
                description
                count_articles
                count_follows
                followed
                user {
                    id
                    name
                }
                allow_submit
                need_approve
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     用户管理专题
	 * TODO
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function UserAdminCategoriesQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query userAdminCategoriesQuery(\$user_id: Int!) {
            categories(user_id: \$user_id, limit: 100, filter: ADMIN) {
                id
                name
                logo
                count_articles
                count_follows
                submit_status
                user {
                    id
                    name
                }
                followed
            }
        }
STR;
		$variables = <<<STR
        {
        "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     关注的专题
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserFollowedCategoriesQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query userFollowedCategoriesQuery(\$user_id: Int!) {
            categories(user_id: \$user_id, limit: 100, filter: FOLLOWED) {
                id
                name
                logo
                count_follows
                count_articles
                followed
                user {
                    id
                    name
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     文集
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testMyCollectionsQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query myCollectionsQuery {
            user {
                id
                collections {
                    id
                    name
                    logo
                    count_articles
                    count_follows
                    user {
                        id
                        name
                    }
                }
            }
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户文集
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserCollectionsQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query userCollectionsQuery(\$user_id: Int!) {
            collections(user_id: \$user_id) {
                id
                name
                logo
                count_articles
                count_follows
                followed
                user {
                    id
                    name
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     关注的文集
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserFollowedCollectionsQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
        query userFollowedCollectionsQuery(\$user_id: Int!) {
            collections(user_id: \$user_id, limit: 100, filter: FOLLOWED) {
                id
                name
                logo
                count_articles
                count_follows
                followed
                user {
                    id
                    name
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户关注
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserFollowingsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
        query userFollowingsQuery(\$user_id: Int!, \$offset: Int) {
            users(user_id: \$user_id, offset: \$offset, filter: FOLLOWINGS) {
                id
                name
                avatar
                count_articles
                count_likes
                followed_status
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id,
            "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户粉丝
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserFollowersQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
      	query userFollowersQuery(\$user_id: Int!, \$offset: Int) {
           users(user_id: \$user_id, offset: \$offset, filter: FOLLOWERS) {
                id
                name
                avatar
                count_articles
                count_likes
                followed_status
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id,
            "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户动态
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserActionsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
       	query userActionsQuery(\$offset: Int, \$user_id: Int!) {
            actions(offset: \$offset, user_id: \$user_id) {
                id
                type
                time_ago
                signUp {
                    time_ago
                }
                tiped {
                    id
                    article {
                        id
                        title
                        images
                        cover
                    }
                }
                postedArticle {
                    id
                    type
                    title
                    description
                    images
                    cover
                    count_likes
                    count_replies
                    hits
                }
                postedComment {
                    id
                    body
                    time_ago
                    user {
                        id
                        name
                        avatar
                    }
                    atUser {
                        id
                        name
                        avatar
                    }
                    article {
                        id
                        type
                        title
                        images
                        cover
                    }
                }
                liked {
                    id
                    article {
                        id
                        type
                        title
                        images
                        cover
                    }
                    comment {
                        id
                        body
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
                            images
                            cover
                        }
                    }
                }
                followed {
                    id
                    user {
                        id
                        name
                        avatar
                    }
                    category {
                        id
                        name
                        logo
                    }
                    collection {
                        id
                        name
                        logo
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "user_id" : $visitor->id,
            "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     专题投稿
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testUserArticlesSubmitStatusQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$category_id = rand(1, 10);

		$query = <<<STR
       	query userArticlesSubmitStatusQuery(\$category_id: Int!) {
            user {
                id
                articles(filter: CATE_SUBMIT_STATUS, category_id: \$category_id) {
                    id
                    type
                    title
                    submit_status
                }
            }
        }
STR;
		$variables = <<<STR
        {
            "category_id" : $category_id
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     推荐关注
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testRecommendFollowsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
       	query recommendFollowsQuery(\$user_id: Int!, \$offset: Int) {
            follows(user_id: \$user_id, filter: USER_CATEGORY, offset: \$offset) {
                id
                user {
                    id
                    name
                    avatar
                    introduction
                    followed_status
                    articles(limit: 2) {
                        id
                        title
                    }
                }
                category {
                    id
                    name
                    logo
                    description
                    count_articles
                    count_follows
                    followed
                    articles(filter: ALL, limit: 2) {
                        id
                        title
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
         "user_id" : $visitor->id,
         "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
	 * @Desc     推荐关注的用户
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testRecommendFollowUsersQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
       	query recommendFollowUsersQuery(\$user_id: Int!, \$offset: Int) {
            follows(user_id: \$user_id, filter: USER, offset: \$offset) {
                id
                user {
                    id
                    name
                    avatar
                    introduction
                    followed_status
                    articles(limit: 2) {
                        id
                        title
                    }
                }
                category {
                    id
                    name
                    logo
                    description
                    count_articles
                    count_follows
                    followed
                    articles(filter: ALL, limit: 2) {
                        id
                        title
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
         "user_id" : $visitor->id,
         "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     推荐关注的专题
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testRecommendFollowCategoriesQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
       	query recommendFollowCategoriesQuery(\$user_id: Int!, \$offset: Int) {
            follows(user_id: \$user_id, filter: CATEGORY, offset: \$offset) {
                id
                user {
                    id
                    name
                    avatar
                    introduction
                    followed_status
                    articles(limit: 2) {
                        id
                        title
                    }
                }
                category {
                    id
                    name
                    logo
                    description
                    count_articles
                    count_follows
                    followed
                    articles(filter: ALL, limit: 2) {
                        id
                        title
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
         "user_id" : $visitor->id,
         "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     文章投稿管理
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testSubmitedArticlesQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
       	query querySubmitedArticles(\$offset: Int, \$limit: Int) {
            user {
                submitedArticles(offset: \$offset, limit: \$limit) {
                    id
                    title
                    submit_status
                    submited_status
                    submitedCategory {
                        id
                        name
                    }
                }
            }
        }
STR;
		$variables = <<<STR
        {
         "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     用户收入
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testIncomeHistoryQuery() {
		$visitor = User::inRandomOrder()
			->first();

		$query = <<<STR
      	query queryIncomeHistory {
           user {
                incomeHistory
            }
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     最近投稿
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */

    public function testArticleRequestCenterQuery() {
        $visitor = User::inRandomOrder()
            ->first();

        $query = <<<STR
       	query queryArticleRequestCenter(\$id: Int) {
		    user(id: \$id) {
		        id
		        categories(filter: LATEST_REQUEST) {
		            id
		            name
		            count_articles
		            count_follows
		            submit_status
		            logo
		        }
		    }
		}
STR;
        $variables = <<<STR
        {
         	"id" : $visitor->id
        }
STR;
        $response = $this->actingAs($visitor)
            ->json("POST", "/graphql", [
                'query' => $query,
                'variables' => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors',
            ]);
	}

	/**
	 * @Desc     查询文章 用户 专题 文集
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testSearchResaultQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$keyword = Category::inRandomOrder()
			->first()
			->name;
		$offset = rand(1, 10);

		$query = <<<STR
       	query SearchResaultQueries(\$keyword: String!, \$type: ArticleType, \$order: ArticleOrder, \$offset: Int) {
            articles(keyword: \$keyword, type: \$type, order: \$order, offset: \$offset, limit: 10) {
                id
                title
                type
                user {
                    name
                }
                description
                cover
            }
            users(keyword: \$keyword, offset: \$offset, limit: 4) {
                id
                name
                avatar
            }
            categories(keyword: \$keyword, offset: \$offset, limit: 4) {
                id
                name
                logo
                count_articles
                count_follows
                followed
                user {
                    id
                    name
                }
            }
            collections(keyword: \$keyword, offset: \$offset, limit: 4) {
                id
                name
                logo
                count_articles
                count_follows
                followed
                user {
                    id
                    name
                }
            }
        }
STR;
		$variables = <<<STR
        {
         	"keyword" : "$keyword",
         	"offset" : "$offset"
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     热搜,搜索记录，未登录返回空。
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testHotSearchAndLogsQuery() {
		$visitor = User::inRandomOrder()
			->first();
		$offset = rand(1, 10);

		$query = <<<STR
    	query hotSearchAndLogsQuery(\$offset: Int) {
            queryLogs(offset: 0) {
                id
                query
            }
            queries(offset: \$offset, limit: 15) {
                id
                query
            }
        }
STR;
		$variables = <<<STR
        {
         "offset" : $offset
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}

	/**
	 * @Desc     删除查询日志
	 * @Author   LYY
	 * @DateTime 2018-07-17
	 * @return   [type]
	 */
	public function testDeleteQueryLog() {
		$visitor = User::inRandomOrder()
			->first();
		$querylog_id = rand(1, 20);

		$query = <<<STR
            mutation deleteQueryLogMutation(\$id: Int) {
                    deleteQueryLog(id: \$id) {
                    id
                    query
                }
            }
STR;
		$variables = <<<STR
        {
          "id":  "$querylog_id"
        }
STR;
		$response = $this->actingAs($visitor)
			->json("POST", "/graphql", [
				'query' => $query,
				'variables' => $variables,
			]);
		$response->assertStatus(200)
			->assertJsonMissing([
				'errors',
			]);
	}
	/**
     * @Desc     投稿请求与处理投稿请求放在一起处理
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testSubmitAndApproveArticleMutation(){
        $article = Article::whereStatus(1)
          ->inRandomOrder()
          ->first();
        
        $cids = [];
        $categories = $article->categories; 
        if( !empty($categories) ){
          $cids = $categories->pluck('id')->toArray();
        }
        $category = Category::whereNotIn('id',$cids)
          ->inRandomOrder()
          ->first();
        
        $is_reject   = rand(0,1);

        $admin     = $category->admins->random();
        $author   = $article->user;
        //投稿 
        $submit_query =  <<<STR
        mutation submitArticleMutation(\$article_id: Int!, \$category_id: Int!) {
            submitArticle(article_id: \$article_id, category_id: \$category_id) {
                id
                submit_status
            }
        }
STR;
        //处理投稿请求
        $approve_query = <<<STR
        mutation approveArticleMutation(\$article_id: Int!, \$category_id: Int!, \$is_reject: Boolean!) {
            approveArticle(article_id: \$article_id, category_id: \$category_id, is_reject: \$is_reject) {
                id
                pivot_status
            }
        }
STR;
        $submit_variables = <<<STR
        {
          "article_id"    :  $article->id,
          "category_id"   :  $category->id
        }
STR;
        //处理投稿请求
        $approve_variables = <<<STR
        {
          "article_id"    :  $article->id,
          "category_id"   :  $category->id,
          "is_reject"     :  $is_reject
        }
STR;
        //投稿请求
        $response = $this->actingAs($author)
            ->json("POST", "/graphql", [
                'query'       => $submit_query,
                'variables'   => $submit_variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
        //处理投稿请求
        $response = $this->actingAs($admin)
            ->json("POST", "/graphql", [
                'query'       => $approve_query,
                'variables'   => $approve_variables,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);

    }
    /**
     * @Desc     给文章点赞
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testLikeArticleMutation(){
        $visitor = User::inRandomOrder()
            ->first();
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $undo = !($visitor->isLiked('articles', $article->id));

        $query = <<<STR
        mutation likeArticleMutation(\$article_id: Int!, \$undo: Boolean) {
            likeArticle(article_id: \$article_id, undo: \$undo) {
                id
                liked
                count_likes
            }
        }
STR;
        $variables = <<<STR
        {
          "article_id"    :  $article->id,
          "undo"          :  $undo
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
     * @Desc    给评论点赞
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testLikeCommentMutation(){
        $visitor = User::inRandomOrder()
            ->first();
        $comment = Comment::inRandomOrder()
            ->first();
        $undo = !($visitor->isLiked('comments', $comment->id));

        $query = <<<STR
        mutation likeCommentMutation(\$comment_id: Int!, \$undo: Boolean) {
            likeComment(comment_id: \$comment_id, undo: \$undo) {
                id
                liked
                likes
            }
        }
STR;
        $variables = <<<STR
        {
          "comment_id"    :  $comment->id,
          "undo"          :  $undo
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
     * @Desc     关注用户
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testFollowUserMutation(){
        $visitor = User::inRandomOrder()
            ->first();
        $user = User::inRandomOrder()
            ->first();
        $undo = !($visitor->isFollow('users', $user->id));

        $query = <<<STR
        mutation followUserMutation(\$user_id: Int!, \$undo: Boolean) {
            followUser(user_id: \$user_id, undo: \$undo) {
                id
                count_follows
                followed_status
            }
        }
STR;
        $variables = <<<STR
        {
          "user_id"       :  $user->id,
          "undo"          :  $undo
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
     * @Desc     关注文集
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testFollowCollectionMutation(){
        $visitor = User::inRandomOrder()
            ->first();
        $collection = Collection::inRandomOrder()
            ->first();
        $undo = !($visitor->isFollow('collections', $collection->id));

        $query = <<<STR
        mutation followCollectionMutation(\$collection_id: Int!, \$undo: Boolean) {
            followCollection(collection_id: \$collection_id, undo: \$undo) {
                id
                count_follows
                followed
            }
        }
STR;
        $variables = <<<STR
        {
          "collection_id" :  $collection->id,
          "undo"          :  $undo
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
     * @Desc     关注专题
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testFollowCategoryMutation(){
        $visitor = User::inRandomOrder()
            ->first();
        $category = Category::inRandomOrder()
            ->first();
        $undo = !($visitor->isFollow('categories', $category->id));

        $query = <<<STR
        mutation followCategoryMutation(\$category_id: Int!, \$undo: Boolean) {
            followCategory(category_id: \$category_id, undo: \$undo) {
                id
                count_follows
                followed
            }
        }
STR;
        $variables = <<<STR
        {
          "category_id" :  $category->id,
          "undo"        :  $undo
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

}