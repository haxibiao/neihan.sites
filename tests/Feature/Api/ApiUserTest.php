<?php
namespace Tests\Feature\Api;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

//这个类下所有的方法都会真正的修改数据库,绝对不能在线上环境跑,只能在本地运行.
class ApiUserTest extends TestCase
{

    public function testUpdateUserInfo()
    {
        $user = \App\User::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->json("POST", "/api/user", [
            'api_token' => $user->api_token,
            'name'      => 'peng_'.time(), //现在用户名不能冲突了
        ]);

        //这里的响应只有1和0没有修改或修改失败响应的都是0
        $response->assertStatus(200);
    }

    public function testUpdateUserAvatar()
    {
        $user = \App\User::orderBy('id', 'desc')->take(5)->get()->random();

        $response = $this->json('POST', "/api/user/save-avatar", [
            'api_token' => $user->api_token,
            'avatar'      => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertStatus(200);
        //get content
        $content = $response->getOriginalContent();

        $response->assertSeeText('storage/avatar');
    }
}