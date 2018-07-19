<?php

namespace Tests\Feature\GraphQL;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * 有关于文章测试的GraphQL API
 * 测试用例的顺序是严格按照 /ainicheng/graphql/category.graphql的顺序来书写的。
 * 本测试用例最后的更新时间是2018年7月19日,后面的同事注意category.graphql文件的变动情况。
 * 下面的测试用例没有将共性的东西进行抽离了，也是为了增加灵活性。
 * 已经加了事务回滚，所以不会对数据库产生变动。
 */
class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @Desc     特殊类型专题
     * @Author   XXM
     * @DateTime 2018-07-16
     */
    public function testSpecialCategoriesQuery()
    {
        $query = <<<STR
        query specialCategoriesQuery {
			categories(filter: SPECIAL, limit: 11) {
				id
				name
				logo
				logo_app
			}
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     返回最新创建的category
     * @Author   XXM
     * @DateTime 2018-07-16
     */
    public function testTopCategoriesQuery()
    {
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
		query topCategoriesQuery(\$offset: Int) {
			categories(offset: \$offset, limit: 12) {
				id
				name
				logo
				count_follows
				latest_follower {
					id
					name
				}
			}
		}
STR;
		$variables = <<<STR
		{
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     Category详情
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$query = <<<STR
    	query categoryQuery(\$id: Int!) {
			category(id: \$id) {
				id
				name
				logo
				user {
					id
					name
					avatar
				}
				followed
				count_articles
				description
				count_follows
				count_authors
				authors {
					id
					name
					avatar
				}
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     分类下热门文章(orderByDesc hits)
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryArticlesByHotQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryArticlesByHotQuery(\$id: Int!, \$offset: Int) {
			articles(category_id: \$id, order: HOT, offset: \$offset) {
				id
				title
				description
				image_url
				time_ago
				user {
					id
					name
					avatar
				}
				hits
				count_comments
				count_tips
				count_likes
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);

    }

    /**
     * @Desc     分类最新文章(orderByDesc Id)
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryArticlesByCommentedQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryArticlesByCommentedQuery(\$id: Int!, \$offset: Int) {
			articles(category_id: \$id, order: COMMENTED, offset: \$offset) {
				id
				title
				description
				image_url
				time_ago
				user {
					id
					name
					avatar
				}
				hits
				count_comments
				count_tips
				count_likes
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     分类下最新创建的文章
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryArticlesByLatestQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryArticlesByLatestQuery(\$id: Int!, \$offset: Int) {
			articles(category_id: \$id, order: LATEST, offset: \$offset) {
				id
				title
				description
				image_url
				time_ago
				user {
					id
					name
					avatar
				}
				hits
				count_comments
				count_tips
				count_likes
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     分类管理人员
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryAdminsQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryAdminsQuery(\$id: Int!, \$offset: Int) {
			users(category_id: \$id, offset: \$offset, filter: CATE_ADMINS) {
				id
				name
				avatar
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     分类作者
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryAuthorsQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryAuthorsQuery(\$id: Int!, \$offset: Int) {
			users(category_id: \$id, offset: \$offset, filter: CATE_AUTHORS) {
				id
				name
				avatar
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     获取关注当前分类的用户
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCategoryFollowersQuery()
    {
    	$id = Category::inRandomOrder()->first()->id;
    	$offset = mt_rand(1, 100);
    	$query = <<<STR
    	query categoryFollowersQuery(\$id: Int!, \$offset: Int) {
			users(category_id: \$id, offset: \$offset, filter: CATE_FOLLOWERS) {
				id
				name
				avatar
			}
		}
STR;
		$variables = <<<STR
		{
			"id":$id,
			"offset":$offset
		}
STR;
		$response = $this->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     创建和删除分类
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testCreateCategoryMutation()
    {
    	//create
    	$user = User::inRandomOrder()->first();
        $name = "testCategory";
        $logo = "test.png";
        $description = "This is a test information";
        $allow_submit = (mt_rand(1, 100)%2) == 0 ? 'true' : 'false';
        $admin_uids = mt_rand(1, User::max('id'));
        $need_approve = (mt_rand(1, 100)%2) == 0 ? 'true' : 'false';
        $query = <<<STR
        mutation createCategoryMutation(\$name: String!, \$logo: String!, \$description: String!, \$allow_submit: Boolean!, \$need_approve: Boolean!, \$admin_uids: String) {
			createCategory(name: \$name, logo: \$logo, description: \$description, allow_submit: \$allow_submit, need_approve: \$need_approve, admin_uids: \$admin_uids) {
				id
				name
				name_en
				logo
				description
				allow_submit
				need_approve
			}
		}
STR;
		$variables = <<<STR
		{
		  "name":"$name",
		  "logo":"$logo",
		  "description":"$description",
		  "allow_submit":$allow_submit,
		  "admin_uids":"$admin_uids",
		  "need_approve":$need_approve
		}
STR;
		$response = $this->actingAs($user)->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
        // delete
    	$user = User::inRandomOrder()->first();
    	$id = $response->json()['data']['createCategory']['id'];
    	$query = <<<STR
    	mutation deleteCategoryMutation(\$id: Int!) {
			deleteCategory(id: \$id) {
				id
			}
		}
STR;
		$variables = <<<STR
		{
		  "id": $id
		}
STR;
		$response = $this->actingAs($user)->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     编辑分类
     * @Author   XXM
     * @DateTime 2018-07-18
     */
    public function testEditCategoryMutation()
    {
    	$user = User::inRandomOrder()->first();
    	$id = Category::inRandomOrder()->first()->id;
        $name = "testEditCategory";
        $logo = "test.png";
        $description = "This is change a changetest information";
        $allow_submit = (mt_rand(1, 100)%2) == 0 ? 'true' : 'false';
        $need_approve = (mt_rand(1, 100)%2) == 0 ? 'true' : 'false';
        $admin_uids = mt_rand(1, User::max('id'));
        $query = <<<STR
        mutation editCategoryMutation(\$id: Int!, \$name: String!, \$logo: String!, \$description: String!, \$allow_submit: Boolean!, \$need_approve: Boolean!) {
			editCategory(id: \$id, name: \$name, logo: \$logo, description: \$description, allow_submit: \$allow_submit, need_approve: \$need_approve) {
				id
				name
				name_en
				logo
				description
				allow_submit
				need_approve
			}
		}

STR;
		$variables = <<<STR
		{
		  "id":$id,
		  "name":"$name",
		  "logo":"$logo",
		  "description":"$description",
		  "allow_submit":$allow_submit,
		  "admin_uids":"$admin_uids",
		  "need_approve":$need_approve
		}
STR;
		$response = $this->actingAs($user)->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }

    /**
     * @Desc     变更专题管理员
     * @Author   XXM
     * @DateTime 2018-07-19
     */
    public function testEditCategoryAdminsMutation()
    {
    	$user = User::inRandomOrder()->first();
    	$admin_uids = $user->id;
    	$id = Category::inRandomOrder()->first()->id;
    	$query = <<<STR
    	mutation editCategoryAdminsMutation(\$id: Int!, \$admin_uids: String) {
			editCategoryAdmins(id: \$id, admin_uids: \$admin_uids) {
				id
				name
				name_en
				logo
				description
				allow_submit
				need_approve
				admins {
					id
					name
				}
			}
		}
STR;
    	$variables = <<<STR
    	{
		  "id": $id,
		  "admin_uids": "$admin_uids"
		}
STR;
		$response = $this->actingAs($user)->json("POST", "/graphql", [
                'query'         => $query,
                'variables'		=> $variables,
        ]);
        $response->assertStatus(200)->assertJsonMissing(['errors']);
    }
}
