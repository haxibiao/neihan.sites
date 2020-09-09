<?php

namespace Tests\Feature\GraphQL;

use App\User;
use App\Assignment;
use App\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Feature\GraphQL\GraphQLTestCase;

class TaskTest extends GraphQLTestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $likes;

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
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * 新用户奖励接口
     * @group task
     */
    public function testNewUserRewordMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/task/Mutation/newUserRewardMutation.graphql');
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
     */
    public function testReceiveTaskMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/task/Mutation/receiveTaskMutation.graphql');
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
     */
    public function testRewardTaskMutation()
    {
        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/task/Mutation/rewardTaskMutation.graphql');
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
     */
    public function testHighPraiseTaskMutation()
    {

        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/task/Mutation/highPraiseTaskMutation.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $task = Task::whereName('应用商店好评')->first();
        //初始化为未提交...
        $this->updateTaskStatus($task->id, 0);

        //提交好评
        $variables = [
            'id'      => $task->id,
            'content' => '应用商店好评',
        ];

        //测试提交是否出错
        $this->runGQL($query, $variables, $headers);
    }

    /**
     * 直播观看任务领取奖励
     * @group task
     */
    // public function testLiveAudienceTaskMutation()
    // {
    //     $token   = $this->user->api_token;
    //     $query   = file_get_contents(__DIR__ . '/task/Mutation/rewardTaskMutation.graphql');
    //     $headers = [
    //         'Authorization' => 'Bearer ' . $token,
    //         'Accept'        => 'application/json',
    //     ];
    //     $task      = Task::whereName('直播任务')->first();
    //     $variables = [
    //         'id' => $task->id,
    //     ];

    //     //先指派
    //     $this->updateTaskStatus($task->id, 1);

    //     //指派中状态的任务，领取奖励.. 应该返回异常
    //     $this->runGQL($query, $variables, $headers);

    //     //assert json has error
    //     //assert text has "任务未完成..."
    // }

    /**
     * 答复任务
     * @group task
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

        $taskRewardMutation = file_get_contents(__DIR__ . '/Task/Mutation/ReplyTaskMutation.graphql');
        $variables          = [
            'id' => $this->task->id,
        ];
        $this->runGuestGQL($taskRewardMutation, $variables, $headers);
    }

    /**
     * 完成任务
     * @group task
     */
    public function testCompleteTaskMutation()
    {
        $token   = $this->user->api_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $taskRewardMutation = file_get_contents(__DIR__ . '/Task/Mutation/CompleteTaskMutation.graphql');
        $variables          = [
            'id' => $this->task->id,
        ];
        $this->runGuestGQL($taskRewardMutation, $variables, $headers);
    }

    /**
     * 喝水任务上报打卡
     * @deprecated 根据DatiTaskSeed可以看出暂时没有喝水任务
     * @group task
     */
    //    public function testDrinkWaterMutation()
    //    {
    //        $token   = $this->user->api_token;
    //        $query   = file_get_contents(__DIR__ . '/task/Mutation/drinkWaterMutation.graphql');
    //        $headers = [
    //            'Authorization' => 'Bearer ' . $token,
    //            'Accept'        => 'application/json',
    //        ];
    //        $variables = [
    //            // FIXME: 喝水任务虽然要求传递一个 id 的参数，但是并没有使用它
    //            'id' => 2,
    //        ];
    //        $this->runGQL($query, $variables, $headers);
    //    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */

    /**
     * 任务列表查询
     * @group task
     */
    public function testTasksQuery()
    {

        $token   = $this->user->api_token;
        $query   = file_get_contents(__DIR__ . '/task/Query/tasksQuery.graphql');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        //新人任务
        $variables = [
            'type' => 'NEW_USER_TASK',
        ];
        $this->runGQL($query, $variables, $headers);

        //每日任务
        $variables = [
            'type' => 'DAILY_TASK',
        ];
        // $response = $this->runGQL($query, $variables, $headers);
        // //校验存在已指派的看视频赚钱
        // $response->assertJsonFragment(['name' => "看视频赚钱"]);

        //校验存在已指派的直播任务
        // $tasks = $response->original['data']['tasks'];
        // $response->assertJsonFragment(['name' => "直播任务"]);

        //奖励任务
        $variables = [
            'type' => 'CUSTOM_TASK',
        ];
        // $response = $this->runGQL($query, $variables, $headers);
        // //校验存在已指派的邀请任务
        // $response->assertJsonFragment(['name' => "邀请任务"]);

        //所有，包含喝水睡觉任务....
        $variables = [
            'type' => 'All',
        ];
        $response = $this->runGQL($query, $variables, $headers);
        //检查除了返回新人任务
        //还有邀请（CUSTOM_TASK）
        //$response->assertJsonFragment(['type' => Task::CUSTOM_TASK]); //TODO:目前没有CUSTOM任务 会报错.
        //看视频赚钱(DAILY_TASK)等另外2个类型的任务
        $response->assertJsonFragment(['type' => Task::DAILY_TASK]);
    }

    /**
     * 更新任务状态
     * @group task
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

    protected function tearDown(): void
    {

        $this->user->forceDelete();
        $this->task->forceDelete();
        parent::tearDown();
    }
}
