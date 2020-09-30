<?php

namespace Tests\Feature\GraphQL;

use App\Assignment;
use App\Post;
use App\Spider;
use App\User;
use Haxibiao\Task\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class Ios1v2Test extends GraphQLTestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'api_token' => str_random(60),
        ]);

        $this->task = factory(Task::class)->create([
            'type' => 2,
        ]);
    }
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testCheckInsQuery
     */
    public function testCheckInsQuery(){

        $query   = file_get_contents(__DIR__ . '/ios1v2/CheckInsQuery.gql');
        //查询签到记录
        $variables = [ ];
        $userHeaders = $this->getRandomUserHeaders();
        $this->startGraphQL($query, $variables,$userHeaders );
    }


    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */
    /**
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testCreatePostContentMutation
     */
    public function testCreatePostContentMutation(){
        $token   = User::find(1)->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v1/CreatePostContentMutation.gql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $image  = UploadedFile::fake()->image('photo.jpg');
        $base64 = 'data:' . $image->getMimeType() . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));

        $post = Post::has('video')->first();
        $video   = $post->video;

        //情形1:创建视频动态
        $variables = [
            'video_id' => $video->id,
            'body'     => '测试创建创建视频动态',
            'type'     => 'POST',
        ];
        $this->startGraphQL($query, $variables, $headers);

        //情形2:创建带图动态
        $variables = [
            'images'       => [$base64],
            'body'         => '测试创建带图动态?',
            'type'         => 'POST',
            'category_ids' => [2],
        ];
        $this->startGraphQL($query, $variables, $headers);

        // 本地视频采集-视频已经存在
        $spider  = Spider::has('video')->latest()->first();
        $video   = $spider->video;
        $variables = [
            'body'         => '测试采集动态?',
            'qcvod_fileid' => $video->qcvod_fileid,
            'share_link'   => $spider->source_url,
        ];
        $this->startGraphQL($query, $variables, $headers);

        // 本地视频采集-视频不存在
        $variables = [
            'body'         => '测试采集动态?',
            'qcvod_fileid' => 5285890806913084003,
            'share_link'   => 'https://v.douyin.com/Jjbke4k/',
        ];
        $this->startGraphQL($query, $variables, $headers);
    }

    /**
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testCreateCheckInMutation
     */
    public function testCreateCheckInMutation(){

        $query   = file_get_contents(__DIR__ . '/ios1v2/CreateCheckInMutation.gql');
        //签到
        $variables = [ ];
        $userHeaders = $this->getRandomUserHeaders();
        $this->startGraphQL($query, $variables,$userHeaders );
    }

    //Task 任务模块

    /**
     * 新用户奖励接口
     * @group task
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testNewUserRewordMutation
     */
    public function testNewUserRewordMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v2/newUserRewardMutation.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'rewardType' => 'VIDEO',
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 领取任务
     * @group task
     * * @group  Ios1v2Test
     * @group  Ios1v2Test_testReceiveTaskMutation
     */
    public function testReceiveTaskMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v2/receiveTaskMutation.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $variables = [
            'id' => 1,
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 获取任务奖励
     * @group task
     * * @group  Ios1v2Test
     * @group  Ios1v2Test_testRewardTaskMutation
     */
    public function testRewardTaskMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v2/rewardTaskMutation.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $variables = [
            'id' => '1',

        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 提交应用商店好评任务
     * @group task
     * * @group  Ios1v2Test
     * @group  Ios1v2Test_testHighPraiseTaskMutation
     */
    public function testHighPraiseTaskMutation()
    {

        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v2/highPraiseTaskMutation.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $task = \App\Task::whereName('应用商店好评')->first();
        //初始化为未提交...
        $this->updateTaskStatus($task->id, 0);

        //提交好评
        $variables = [
              'user_id'=>$this->user->id,
              'account'=>'1222222',
              'images'=>[$this->getBase64ImageString()],
              'info'=>'测试'
        ];

        //测试提交是否出错
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 答复任务
     * @group task
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testReplyTaskMutation
     */
    public function testReplyTaskMutation()
    {
        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        //重置任务为待完成状态
        $this->user->tasks()->detach($this->task->id);
        $this->user->tasks()->attach($this->task->id, ['status' => Task::NEW_USER_TASK]);

        $taskRewardMutation = file_get_contents(__DIR__ . '/ios1v2/ReplyTaskMutation.graphql');
        $variables          = [
            'id' => $this->task->id,
        ];
        $this->runGuestGQL($taskRewardMutation, $variables, $headers);
    }

    /**
     * 完成任务
     * @group task
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testCompleteTaskMutation
     *
     */
    public function testCompleteTaskMutation()
    {
        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $taskRewardMutation = file_get_contents(__DIR__ . '/ios1v2/CompleteTaskMutation.graphql');
        $variables          = [
            'id' => $this->task->id,
        ];
        $this->runGuestGQL($taskRewardMutation, $variables, $headers);
    }


    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * 任务列表查询
     * @group task
     * @group  Ios1v2Test
     * @group  Ios1v2Test_testTasksQuery
     */
    public function testTasksQuery()
    {

        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/ios1v2/tasksQuery.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        //新人任务
        $variables = [
            'type' => 'NEW_USER_TASK',
        ];
        $response1 = $this->runGQL($query, $variables, $headers);
        $response1->assertJsonFragment(['type' => Task::NEW_USER_TASK]);

        //每日任务
        $variables = [
            'type' => 'DAILY_TASK',
        ];
        $response2 = $this->runGQL($query, $variables, $headers);
        $response2->assertJsonFragment(['type' => Task::DAILY_TASK]);

        //奖励任务
        $variables = [
            'type' => 'CUSTOM_TASK',
        ];
        $response3 = $this->runGQL($query, $variables, $headers);


        //所有，包含喝水睡觉任务....
        $variables = [
            'type' => 'All',
        ];
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 更新任务状态
     */
    public function updateTaskStatus($task_id, $status)
    {
        $userAssignment = Assignment::where('user_id', $this->user->id)->where('task_id', $task_id)->first();
        if (is_null($userAssignment)) {
            $assignment          = new Assignment();
            $assignment->user_id = $this->user->id;
            $assignment->task_id = $task_id;
            $assignment->status  = $status;
            $assignment->save();
            return $assignment;
        } else {
            $assignment = Assignment::where('id', $userAssignment->id)->first();
            $assignment->update(['status' => $status]);
        }
    }
}
