<?php
namespace Database\Seeders;

use App\Contribute;
use Illuminate\Database\Seeder;

class ContributeSeeder extends Seeder
{
    public function run()
    {
        $contributes = Contribute::all();

        foreach ($contributes as $contribute) {

            $contributed = $contribute->contributed_type;

            if ($contributed == "articles") {
                $contribute->remark = '发布视频奖励';
            }
            if ($contributed == "AD") {
                $contribute->remark = '观看广告奖励';
            }

            if ($contributed == "AD_VIDEO") {
                $contribute->remark = '观看激励视频奖励';
            }
            if ($contributed == "issues") {
                $contribute->remark = '悬赏奖励';
            }
            if ($contributed == "usertasks") {
                $contribute->remark = '任务奖励';
            }

            $contribute->timestamps = false;
            echo 'id' . $contribute->id;

            $contribute->save();
        }
    }
}
