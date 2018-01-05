<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


//这个类下所有的方法都会真正的修改数据库,绝对不能在线上环境跑,只能在本地运行.
class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFollowApi(){
    	$user=\App\User::orderBy('id','desc')->take(5)->get()->random();

    	$category=\App\Category::orderBy('id','desc')->take(5)->get()->random();

    	$response=$this->post("/api/follow/$category->id/categories",[
                "api_token"=>$user->api_token,
    	]);

        $response->assertStatus(200);
    }

    public function testLikeApi(){
    	$user=\App\User::orderBy('id','desc')->take(5)->get()->random();

    	$article=\App\Article::orderBy('id','desc')->take(5)->get()->random();

    	$response=$this->post("/api/like/$article->id/article",[
                'api_token'=>$user->api_token,
        ]);
        $response->assertStatus(200);
        $response->assertSeeText('liked');
    }

    public function testUpdateUserInfo(){
    	$user=\App\User::orderBy('id','desc')->take(5)->get()->random();
        
    	$response=$this->post("/api/user/$user->id/update",[
                'api_token'=>$user->api_token,
                'name'=>'peng',
    	]);
        
        //这里的响应只有1和0没有修改或修改失败响应的都是0
    	$response->assertStatus(200);
    }

    public function testUpdateUserAvatar(){
    	$user=\App\User::orderBy('id','desc')->take(5)->get()->random();

    	$response = $this->json('POST', "/api/user/$user->id/avatar", [
    		 'api_token'=>$user->api_token,
             'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);
        
        $response->assertStatus(200);
        //get content
        $content = $response->getOriginalContent();

        $response->assertSeeText('storage');
    }

    public function testComment(){
        $user=\App\User::orderBy('id','desc')->take(5)->get()->random();
        $article=\App\Article::orderBy('id','desc')->take(5)->get()->random();

        $response =$this->post("/api/comment",[
               'api_token'=>$user->api_token,
               'body'=>'test',
               'commentable_id'=>$article->id,
               'commentable_type'=>"articles",
               'is_new'=>true,
               'is_replay_comment'=>false,
               'likes'=>0,
               'lou'=>1,
               'reports'=>0,
               'time'=>time(),
        ]);
        $response->assertStatus(200);
        $content = $response->getOriginalContent();
        $response->assertJson([
             'body'=>'test',
        ]);     
    }

    //测试聊天室能不能正常被创建

    public function testChats(){
        $user=\App\User::orderBy('id','desc')->take(5)->get()->random();

        $response =$this->get("/api/notification/chats",[
              'api_token'=>$user->api_token,
        ]);

        $response->assertStatus(302);
    }

    //测试聊天消息能否正常发出
    public function testChatMassage()
    {
        $chat=\App\Chat::orderBy('id','desc')->take(1)->first();
        //TODO::正则写的可能不健壮,日后仔细研究.
        $preg='/\[\d+,?"(\S*)?"]/';
        preg_match_all($preg,$chat->uids,$match);
        $user_id= $match[1][0];

        $user=\App\User::find($user_id);
        
        $response =$this->post("/api/notification/chat/$chat->id/send",[
               'api_token'=> $user->api_token,
               'message'=>'test',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
             'message'=>'test',
        ]); 
    }

}
