<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrawlTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrawl()
    {
    	$response=$this->post('/api/articleCrawl',[
    			'title'=>'shuai',
    			'body'=>'shuaiqi',
    			'category_name'=>'唯美图片',
    	]);

    	$response->assertStatus(200);
    }

}
