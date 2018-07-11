<?php

namespace Tests\Feature;

use Tests\TestCase;

//这个类下所有的方法都会真正的修改数据库,绝对不能在线上环境跑,只能在本地运行.
class ApiChatTest extends TestCase
{

    //测试聊天室能不能正常被创建
    public function testChats()
    {
        $user = \App\User::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->get("/api/notification/chats", [
            'api_token' => $user->api_token,
        ]);

        $response->assertStatus(302);
    }

    //测试聊天消息能否正常发出
    public function testChatMassage()
    {
        $chat    = \App\Chat::orderBy('id', 'desc')->take(1)->first();
        $uids    = json_decode($chat->uids);
        $user_id = $uids[0];

        $user = \App\User::find($user_id);

        $response = $this->json("POST", "/api/notification/chat/$chat->id/send", [
            'api_token' => $user->api_token,
            'message'   => 'test',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'test',
        ]);
    }

}
