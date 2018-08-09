<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiArticleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @Desc     测试创作编辑器的创建文章功能
     * @DateTime 2018-07-15
     * @return   [type]     [description]
     */
    public function testCreateArticle(){

        $user = User::inRandomOrder()
            ->first();
        $collection = $user->collections
            ->random();

        $faker = \Faker\Factory::create('zh_CN');
        $body  = $faker->text($maxNbChars = 500) ;
        $title = $faker->title;

        $response = $this->actingAs($user)
            ->json("POST", "/api/collection/{$collection->id}/article/create", [ 
                'api_token'   => $user->api_token,
                'body'        => $body,
                'title'       => $title,
            ]);
        $response->assertStatus(200);
    } 
    /**
     * @Desc     测试创建Post
     * @DateTime 2018-07-23
     * @return   [type]     [description]
     */
    public function testCreatePost(){ 

        $user = User::inRandomOrder()
            ->first();

        $faker = \Faker\Factory::create('zh_CN');
        $body  = $faker->text($maxNbChars = 150) ;
        $title = $faker->title;
 
        $response = $this->actingAs($user)
            ->json("POST", "/post", [ 
                'body'        => $body,
                'title'       => $title,
                'image_urls'  =>['https://www.ainicheng.com/storage/img/73916.png','https://www.ainicheng.com/storage/img/73917.png']
            ]);
        $response->assertStatus(302);
    } 
}
