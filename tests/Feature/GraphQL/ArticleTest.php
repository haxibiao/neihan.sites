<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Article;
use App\Collection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * 有关于文章测试的GraphQL API
 */
class ArticleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @Desc     测试文章打赏功能
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testTipArticle()
    { 
        $sponsor = User::inRandomOrder()
            ->first();
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $author = $article->user;
        $amount = rand(1,10);
        $msg = "测试一下";
        $query = <<<STR
        mutation tipArticleMutation(\$id: Int!, \$amount: Int!, \$message: String!) {
            tipArticle(id: \$id, amount: \$amount, message: \$message) {
                id
                title
            }
        }
STR;
        $variables = <<<STR
        {
          "id":  $article->id,
          "amount": $amount,
          "message": "$msg"
        }
STR;
        $response = $this->actingAs($author)
            ->json("POST", "/graphql", [
                'query'       => $query,
                'variables'   => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data'=> [
                    'tipArticle'=>[
                        'id'    => $article->id,
                        'title' => $article->title,
                    ]
                ]
            ]);
    }

    /**
     * @Desc     创建文章
     * @Author   czg
     * @DateTime 2018-07-14
     */
    public function testCreatedArticle() 
    { 
        $author = User::inRandomOrder()
            ->first();
        $is_publish = random_int(0, 1);
        
        $faker = \Faker\Factory::create('zh_CN');
        $body  = $faker->text($maxNbChars = 500) ;
        $title = $faker->title;

        $query = <<<STR
        mutation createdArticleMutation(\$title: String!, \$body: String!, \$is_publish: Boolean) {
            createArticle(title: \$title, body: \$body, is_publish: \$is_publish) {
                id
                title
                status
                body
            }
        }
STR;
        $variables = <<<STR
        {
          "title"       :  "$title",
          "body"        :  "$body",
          "is_publish"  :  $is_publish
        }
STR;
        $response = $this->actingAs($author)
            ->json("POST", "/graphql", [
                'query'         => $query, 
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    "createArticle"=>[
                        'title'     => "$title",
                        'body'      => "$body",
                        'status'    => $is_publish
                    ]
                ]
            ]);
    }

    /**
     * @Desc     编辑文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testEditArticle()
    { 
        $article = Article::inRandomOrder()
            ->first();
        $old_status = $article->status;
        
        $author = $article->user;
        $is_publish = random_int(0, 1);
        
        $faker = \Faker\Factory::create('zh_CN');
        $body  = $faker->text($maxNbChars = 500);
        $title = $faker->title;

        $query = <<<STR
        mutation editArticleMutation(\$id: Int!, \$title: String!, \$body: String!, \$is_publish: Boolean) {
            editArticle(id: \$id, title: \$title, body: \$body, is_publish: \$is_publish) {
                id
                type
                title
                status
                body
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id,
          "title"     : "$title",
          "body"      : "$body",
          "is_publish": $is_publish
        }
STR;
        $response = $this->actingAs($author)
            ->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    "editArticle"=>[
                        'title'  => "$title",
                        'status' => $is_publish==1?1:$old_status,
                        'body'   => "$body",
                    ]
                ]
            ]);
    }
    /**
     * @Desc     发布文章
     * @Author   czg
     * @DateTime 2018-07-13
     * @return   [type]     [description]
     */
    public function testPublishArticle()
    { 
        $article = Article::whereStatus(0)
            ->inRandomOrder()
            ->first();
        $author = $article->user;

        $query = <<<STR
        mutation publishArticleMutation(\$id: Int!) {
            publishArticle(id: \$id) {
                id
                title
                status
                body
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
            ->assertJson([
                'data' => [
                    "publishArticle"=>[
                        'title'  => $article->title,
                        'status' => 1,
                        'body'   => $article->body,
                    ]
                ]
            ]);
    }
    /**
     * @Desc     文章设为私密
     * @Author   czg
     * @DateTime 2018-07-13
     * @return   [type]     [description]
     */
    public function testUnPublishArticle()
    { 
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $author = $article->user;

        $query = <<<STR
        mutation unpublishArticleMutation(\$id: Int!) {
            unpublishArticle(id: \$id) {
                id
                title
                status
                body
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
            ->assertJson([
                'data' => [
                    "unpublishArticle"=>[
                        'id'     => $article->id,
                        'title'  => $article->title,
                        'body'   => $article->body,
                        'status' => 0,
                    ]
                ]
            ]);
    }

    /**
     * @Desc     移动文章
     * @Author   czg
     * @DateTime 2018-07-13
     * @return   [type]     [description]
     */
    public function testMoveArticle()
    { 
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $collection = Collection::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $author = $article->user;

        $query = <<<STR
        mutation moveArticleMutation(\$article_id: Int!, \$collection_id: Int!) {
            moveArticle(article_id: \$article_id, collection_id: \$collection_id) {
                id
                title
                status
                body
                collection {
                    id
                    name
                }
            }
        }
STR;
        $variables = <<<STR
        {
          "article_id"        : $article->id,
          "collection_id"     : $collection->id
        }
STR;
        $response = $this->actingAs($author)
            ->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200) 
            ->assertJson([
                'data' => [
                    "moveArticle"=>[
                        'id'     => $article->id,
                        'title'  => $article->title,
                        'status' => $article->status,
                        'collection'=>[
                            'id'    => $collection->id,
                            'name'  => $collection->name,
                        ],
                    ]
                ]
            ]);
    }
    
    
}
