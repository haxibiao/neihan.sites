<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Article;
use App\Collection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * 该测试用例最后的更新时间为2018年7月17日19时
 * 有关于文章测试的GraphQL API
 * 测试用例的顺序是严格按照 /ainicheng/graphql/article.graphql的顺序来书写的。
 * 后面的同事注意article.graphql文件的变动情况。
 * 下面的测试用例没有将共性的东西进行抽离了，也是为了增加灵活性。
 * 已经加了事务回滚，所以不会对数据库产生变动。
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
                        'status' => 1
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

    /**
     * @Desc     toggle 收藏/取消收藏 文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testFavoriteArticle()
    { 
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $author = $article->user;
        $undo   = random_int(0, 1);
        $visitors = User::where('id', '<>', $author->id)
            ->inRandomOrder()
            ->first();
        
        $query = <<<STR
        mutation favoriteArticleMutation(\$article_id: Int!, \$undo: Boolean) {
            favoriteArticle(article_id: \$article_id, undo: \$undo) {
                id
                favorited
            }
        }  
STR;
        $variables = <<<STR
        {
          "article_id"        : $article->id,
          "undo"              : $undo
        }
STR;
        $response = $this->actingAs($visitors)
            ->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)  
            ->assertJson([
                'data' => [
                    "favoriteArticle"=>[
                        'id'         => $article->id,
                        'favorited'  => $undo?0:1,
                    ]
                ]
            ]);
    }

    /**
     * @Desc     toggle 查询文章内容
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testArticleContentQuery()
    { 
        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        
        $query = <<<STR
        query articleContentQuery(\$id: Int!) {
            article(id: \$id) {
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
          "id"        : $article->id
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,  
                'variables'     => $variables,
            ]);
        $response->assertStatus(200)  
            ->assertJson([
                'data' => [
                    "article"=>[
                        'id'         => $article->id,
                        'type'       => $article->type,
                        'title'      => $article->title,
                        'status'     => $article->status
                    ]
                ]
            ]);
    }
    /**
     * @Desc     按热度获取文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testRankingArticleQuery(){
        
        $in_days  = array_random([7,30]);

        $query = <<<STR
        query RankingArticleQuery(\$in_days: Int!) {
            articles(in_days: \$in_days, order: HOT) {
                id
                type
                title
                status
                hits
                body 
                time_ago
                has_image
                images
                cover
                hits
                count_likes
                count_replies
                count_tips
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
            }
        }
STR;
        $variables = <<<STR
        {
          "in_days"        : $in_days
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
     * @Desc     [desc]
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testTrashQuery(){

        $article = Article::inRandomOrder()
            ->first();

        $query = <<<STR
        query trashQuery(\$id: Int!) {
            article(id: \$id) {
                id
                type
                title
                body
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
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
     * @Desc     举报文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testReportArticleMutation(){

        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();
        $type    = array_random(['广告或垃圾信息','抄袭或转载','其他']);
        $reason  =  'balabala';
        
        $author = $article->user;
        $visitors = User::where('id', '<>', $author->id)
            ->inRandomOrder()
            ->first();


        $query = <<<STR
        mutation reportArticleMutation(\$id: Int!, \$type: String!, \$reason: String) {
            reportArticle(id: \$id, type: \$type, reason: \$reason) {
                id
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id,
          "type"      : "$type",
          "reason"    : "$reason"
        }
STR;
        $response = $this->actingAs($visitors)
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
     * @Desc     文章详情
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testArticleQuery(){

        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();

        $query = <<<STR
        query articleQuery(\$id: Int!) {
            article(id: \$id) {
                id
                type
                title
                body
                video_url
                user {
                    id
                    name
                    avatar
                    introduction
                    count_articles
                    count_likes
                    tip_words
                    followed_status
                }
                time_ago
                count_words
                hits
                liked
                favorited
                count_likes
                count_tips
                count_replies
                collection {
                    id
                    name
                }
                categories {
                    id
                    name
                }
                tipedUsers {
                    id
                    name
                    avatar
                }
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
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
     * @Desc     
     * @Author   czg
     * @DateTime 2018-07-14 
     * @return   [type]     [description]
     */
    public function testArticleLikesQuery(){

        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();

        $query = <<<STR
        query articleLikesQuery(\$id: Int!) {
            article(id: \$id) {
                id
                type
                liked
                count_likes
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
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
     * @Desc     文章草稿
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testDraftQuery(){

        $article = Article::whereStatus(1)
            ->inRandomOrder()
            ->first();

        $query = <<<STR
        query draftQuery(\$id: Int!) {
            article(id: \$id) {
                id
                type
                title
                body
                time_ago
                count_words
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $article->id
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
     * @Desc     
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testTopArticleWithImagesQuery(){
        $query = <<<STR
        query topArticleWithImagesQuery {
            articles(filter: TOP, limit: 7) {
                id
                type
                title
                top_image
            }
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,
                ''
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }

    /**
     * @Desc     热门文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testHotArticlesQuery(){
        $offset = array_rand([0,10,20,30]);
        
        $query = <<<STR
        query hotArticlesQuery(\$offset: Int) {
            articles(offset: \$offset) {
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
                hits
                count_likes
                count_replies
                count_tips
            }
        }
STR;
        $variables = <<<STR
        {
          "offset"        : $offset
        }
STR;
        $response = $this->json("POST", "/graphql", [
                'query'         => $query,
            ]);
        $response->assertStatus(200)
            ->assertJsonMissing([
                'errors'
            ]);
    }
    /**
     * @Desc     推荐文章
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testRecommendArticlesQuery(){
        $offset = array_rand([0,10,20,30]);
        $limit  = array_rand([10,20,50,100]);

        $query = <<<STR
        query recommendArticlesQuery(\$offset: Int, \$limit: Int) {
            articles(offset: \$offset, limit: \$limit) {
                id
                type
                title
                description
                time_ago
                has_image
                images
                cover
                user {
                    avatar
                    name
                    id
                }
                category {
                    id
                    name
                    logo
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
          "offset"        : $offset,
          "limit"         : $limit
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
     * @Desc     文章投稿推荐
     * @Author   czg
     * @DateTime 2018-07-14
     * @return   [type]     [description]
     */
    public function testQueryArticleRequesRecommend(){

        $visitors = User::inRandomOrder()
            ->first();

        $query = <<<STR
        query queryArticleRequesRecommend(\$id: Int) {
            user(id: \$id) {
                id
                categories(filter: RECOMMEND) {
                    id
                    name
                    count_articles
                    count_follows
                    logo
                }
            }
        }
STR;
        $variables = <<<STR
        {
          "id"        : $visitors->id
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
}
