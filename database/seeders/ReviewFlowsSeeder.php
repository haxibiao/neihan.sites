<?php
namespace Database\Seeders;

use Haxibiao\Task\ReviewFlow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewFlowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('review_flows')->truncate();

        $reviewFlows = [
            [
                'name'                => '喝水赚钱',
                'check_functions'     => ['checkDrinkWater'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => '',
            ],
            [
                'name'                => '睡觉赚钱',
                'check_functions'     => ['checkSleep'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => '',
            ],
            [
                'name'                => '视频发布',
                'check_functions'     => ['checkPublishVideo'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Article',
            ],
            [
                'name'                => '看视频赚钱',
                'check_functions'     => ['checkTodayWatchRewardVideoCount'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Contribute',
            ],
            [
                'name'                => '完善头像',
                'check_functions'     => ['checkUserHasAvatar'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Profile',
            ],
            [
                'name'                => '绑定手机号',
                'check_functions'     => ['checkUserHasPhone'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'User',
            ],
            [
                'name'                => '修改性别和生日',
                'check_functions'     => ['checkUserGenderAndBirthday'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Profile',
            ],
            [
                'name'                => '应用商店好评',
                'check_functions'     => ['checkAppStoreComment'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => '',
            ],
            [
                'name'                => '最大观众数量',
                'check_functions'     => ['checkAudienceCount'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'UserLive',
            ],
            [
                'name'                => '点赞数量统计',
                'check_functions'     => ['checkLikesCount'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Like',
            ],
            [
                'name'                => '邀请用户统计',
                'check_functions'     => ['checkInviteUser'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Invite',
            ],
            [
                'name'                => '回答问题',
                'check_functions'     => ['checkSolutionCount'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Resolution',
            ],
            [
                'name'                => '发布问题',
                'check_functions'     => ['checkIssueCount'],
                'need_owner_review'   => false,
                'need_offical_review' => false,
                'type'                => 1, //1代表只能后台用户选用
                'review_class'        => 'Issue',
            ],
        ];
        foreach ($reviewFlows as $reviewFlow) {
            ReviewFlow::firstOrCreate($reviewFlow);
        }
    }
}
