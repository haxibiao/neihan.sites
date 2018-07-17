<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use App\User;
use App\Article;
use App\Chat;

use Illuminate\Foundation\Testing\DatabaseTransactions;
/**
 * 该测试用例最后的更新时间为2018年7月17日19时
 */
class ChatTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @Desc     聊天详情
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testChatQuery(){
    	$users = User::inRandomOrder()
    		->take(2)
            ->get();
        $me        = $users->first();
        $chat_user = $users->last();

        $query = <<<STR
        query chatQuery(\$with_id: Int!) {
    		  chat(with_id: \$with_id) {
    		    id
    		  }
    		}
STR;
        $variables = <<<STR
        {
          "with_id":  $chat_user->id
        }
STR;
        $response = $this->actingAs($me)
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
     * @Desc     用户消息列表
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testChatsQuery(){

    	$user = User::inRandomOrder()->first();
        $query = <<<STR
        query chatsQuery(\$offset: Int) {
    		  user { 
    		    id
    		    chats(offset: \$offset) {
    		      id
    		      lastMessage {
    		        id
    		        message
    		      }
    		      withUser {
    		        id
    		        name
    		        avatar
    		      }
    		      unreads
    		      time_ago
    		      updated_at
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
     * @Desc     发起聊天
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testCreateChatMutation(){
    	$users = User::inRandomOrder()
    		->take(2)
            ->get();
        $me        = $users->first();
        $chat_user = $users->last();

        $query = <<<STR
        mutation createChatMutation(\$id: Int!) {
    		  createChat(with_id: \$id) {
    		    id
    		    withUser {
    		      id
    		      name
    		      avatar
    		    }
    		  }
    		}
STR;
        $variables = <<<STR
        {
          "id":  $chat_user->id
        }
STR;
        $response = $this->actingAs($me)
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
     * @Desc     查询聊天消息
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testMessagesQuery(){
    	while(true){
    		$chat  = Chat::inRandomOrder()->first();
    		//防止后台脏乱数据的影响
			if( count($chat->users)>=2 ){
				$user = $chat->users->random();
				break;
			}
    	}
        $query = <<<STR
        query messagesQuery(\$chat_id: Int, \$offset: Int) {
      		  messages(chat_id: \$chat_id, offset: \$offset) {
      		    id
      		    message
      		    time_ago
      		    created_at
      		    user {
      		      id
      		      name
      		      avatar
      		    }
      		    images {
      		      id
      		      url
      		    }
      		  }
      		}
STR;
        $variables = <<<STR
        {
          "chat_id":  $chat->id
        }
STR;
        $response = $this->actingAs($user)
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
     * @Desc     发送聊天消息
     * @Author   czg
     * @DateTime 2018-07-17
     * @return   [type]     [description]
     */
    public function testSendMessageMutation(){
    	while(true){
    		$chat  = Chat::inRandomOrder()->first();
    		//防止后台脏乱数据的影响
			if( count($chat->users)>=2 ){
				$user = $chat->users->random();
				break;
			}
    	}
        $query = <<<STR
        mutation sendMessageMutation(\$chat_id: Int!, \$message: String!) {
    		  sendMessage(chat_id: \$chat_id, message: \$message) {
    		    id
    		    message
    		    time_ago
    		    created_at
    		    user {
    		      id
    		      name
    		      avatar
    		    }
    		    images {
    		      id
    		      url
    		    }
    		  }
    		}
STR;
        $variables = <<<STR
        {
          "chat_id":  $chat->id,
          "message":  "测试"
        }
STR;
        $response = $this->actingAs($user)
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