<?php

namespace Tests\Feature\Api\Video;

use Tests\TestCase;
use App\User;
use App\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiVideoTest extends TestCase
{
    use DatabaseTransactions; 
    /**
     * @Desc     测试视频上传
     * @Author   czg
     * @DateTime 2018-07-13
     * @return   [type]     [description]
     */
    public function testUploadVideo()
    {
        $user     = User::inRandomOrder()
            ->first(); 
        $video_id = Video::max('id') + 1;
        $response = $this->postJson(
            '/api/video/save?api_token=' . $user->api_token, 
            [ 
                'video' => new UploadedFile("/test.mp4", "test.mp4", null, null, null, true),
            ]); 
        $response->assertStatus(200);
        //get content
        $content = $response->getOriginalContent();
    }



}
