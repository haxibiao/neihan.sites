<?php

namespace Tests\Feature\GraphQL;

use App\User;
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
            "id"      : $visitor->id,
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

	/*
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
		                 "id" :
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
	*/

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
			->first
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

}